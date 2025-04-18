<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $carts = Cart::with('productVariant')->where('user_id', $user->id)->get();
        $response = Http::get('https://provinces.open-api.vn/api/?depth=2');
        $provinces = $response->json();

        if ($carts->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Tính tổng tạm tính
        $subtotal = $carts->sum(function ($cart) {
            return $cart->price * $cart->quantity;
        });

        // Get applied coupon from session if exists
        $coupon = null;
        $discount = 0;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                // Kiểm tra điều kiện min_order_value
                if ($subtotal >= $coupon->min_order_value) {
                    // Tính số tiền được giảm
                    if ($coupon->discount_percent > 0) {
                        $discount = ($subtotal * $coupon->discount_percent) / 100;
                    } else {
                        $discount = $coupon->discount_amount;
                    }
                }
            }
        }

        $total = $subtotal - $discount;

        return view('checkout', compact('carts', 'subtotal', 'provinces', 'coupon', 'discount', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code'
        ]);

        $user = Auth::user();
        $carts = Cart::with('productVariant')->where('user_id', $user->id)->get();
        
        // Calculate cart subtotal
        $subtotal = $carts->sum(function ($cart) {
            return $cart->price * $cart->quantity;
        });

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        if (!$coupon->isValid()) {
            return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn.');
        }

        if ($subtotal < $coupon->min_order_value) {
            return redirect()->back()->with('error', 'Đơn hàng cần đạt giá trị tối thiểu ' . number_format($coupon->min_order_value, 0, ',', '.') . '₫ để sử dụng mã giảm giá này.');
        }

        // Store coupon code in session
        Session::put('coupon_code', $request->coupon_code);

        return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công.');
    }

    public function removeCoupon()
    {
        Session::forget('coupon_code');
        return redirect()->back()->with('success', 'Đã xóa mã giảm giá.');
    }

    public function processPayment(Request $request)
    {
        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'cod') {
            return $this->codPayment($request);
        } elseif ($paymentMethod === 'vnpay') {
            // Lưu thông tin đơn hàng vào session
            session([
                'checkout_info' => [
                    'recipient_name' => $request->recipient_name,
                    'recipient_phone' => $request->recipient_phone,
                    'recipient_address' => $request->recipient_address,
                    'province_code' => $request->province_code,
                    'district_code' => $request->district_code,
                ]
            ]);

            // Chuyển đến xử lý thanh toán VNPAY
            return $this->checkoutVnpay($request);
        } elseif ($paymentMethod === 'momo') {
            return redirect()->route('checkout.momo');
        }

        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ');
    }

    public function codPayment(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::with('productVariant')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::beginTransaction();
        try {
            $subtotal = $carts->sum(function ($cart) {
                return $cart->price * $cart->quantity;
            });

            // Check if a coupon is applied
            $discount = 0;
            $couponCode = Session::get('coupon_code');
            
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_order_value) {
                    if ($coupon->discount_percent > 0) {
                        $discount = ($subtotal * $coupon->discount_percent) / 100;
                    } else {
                        $discount = $coupon->discount_amount;
                    }
                }
            }

            $total = $subtotal - $discount;

            // Tạo đơn hàng mới
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'coupon_code' => $couponCode ?? null,
                'discount_amount' => $discount,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            // Duyệt qua các sản phẩm trong giỏ hàng và tạo order details
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cart->product_variant_id,
                    'price' => $cart->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->price * $cart->quantity,

                    // Thêm thông tin giao hàng vào order_details
                    'recipient_name' => $request->input('recipient_name'),
                    'recipient_phone' => $request->input('recipient_phone'),
                    'recipient_address' => $request->input('recipient_address'),
                    'province_code' => $request->input('province_code'),
                    'district_code' => $request->input('district_code'),
                    'payment_method' => 'cod',
                ]);

                // Trừ số lượng sản phẩm
                $productVariant = $cart->productVariant;
                $productVariant->quantity -= $cart->quantity;
                $productVariant->save();
            }

            // Xóa giỏ hàng và mã giảm giá khỏi session
            Cart::where('user_id', $user->id)->delete();
            Session::forget('coupon_code');

            DB::commit();

            return redirect()->route('orders.success')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đặt hàng: ' . $e->getMessage());
        }
    }

    // Update VNPAY payment method to include coupon
    public function checkoutVnpay(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::with('productVariant')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Calculate subtotal
        $subtotal = $carts->sum(function ($cart) {
            return $cart->price * $cart->quantity;
        });

        // Apply coupon if exists
        $discount = 0;
        $couponCode = Session::get('coupon_code');
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $subtotal >= $coupon->min_order_value) {
                if ($coupon->discount_percent > 0) {
                    $discount = ($subtotal * $coupon->discount_percent) / 100;
                } else {
                    $discount = $coupon->discount_amount;
                }
            }
        }

        $total = $subtotal - $discount;

        $orderCode = 'VNP' . time();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total,
            'coupon_code' => $couponCode ?? null,
            'discount_amount' => $discount,
            'payment_method' => 'vnpay',
            'status' => 'unpaid',
        ]);

        $checkoutInfo = session('checkout_info');
        $checkoutInfo['order_id'] = $order->id;
        session(['checkout_info' => $checkoutInfo]);

        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = config('services.vnpay.callback');
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = 'Thanh toán đơn hàng ' . $orderCode;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url .= "?" . $query;
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnp_SecureHash;

        return redirect($vnp_Url);
    }

    // Update VNPAY callback to work with coupon
    public function vnpayCallback(Request $request)
    {
        if ($request->vnp_ResponseCode != '00') {
            return redirect()->route('cart.index')->with('error', 'Thanh toán thất bại hoặc bị hủy.');
        }

        $checkoutInfo = session('checkout_info');
        if (!$checkoutInfo) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        $order = Order::find($checkoutInfo['order_id']);
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $carts = Cart::with('productVariant')->where('user_id', $user->id)->get();

            // Tạo chi tiết đơn hàng và cập nhật số lượng sản phẩm
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cart->product_variant_id,
                    'price' => $cart->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->price * $cart->quantity,
                    'recipient_name' => $checkoutInfo['recipient_name'],
                    'recipient_phone' => $checkoutInfo['recipient_phone'],
                    'recipient_address' => $checkoutInfo['recipient_address'],
                    'province_code' => $checkoutInfo['province_code'],
                    'district_code' => $checkoutInfo['district_code'],
                    'payment_method' => 'vnpay',
                ]);

                // Cập nhật số lượng sản phẩm
                $productVariant = $cart->productVariant;
                $productVariant->quantity -= $cart->quantity;
                $productVariant->save();
            }

            // Cập nhật trạng thái đơn hàng
            $order->status = 'paid';
            $order->save();

            // Xóa giỏ hàng và mã giảm giá khỏi session
            Cart::where('user_id', $user->id)->delete();
            Session::forget('coupon_code');

            DB::commit();
            session()->forget('checkout_info'); // Xóa thông tin đơn hàng khỏi session

            return redirect()->route('orders.success')->with('success', 'Thanh toán thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Lỗi khi xử lý đơn hàng: ' . $e->getMessage());
        }
    }

    // Rest of your methods remain the same
    public function success()
    {
        return view('orders.success');
    }

    public function momo()
    {
        return view('payment.momo');
    }

    public function orderHistory()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->with('orderDetails.productVariant.product') // Eager load quan hệ
            ->get();

        return view('history', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này.');
        }

        $order->load('orderDetails.productVariant.product');

        return view('detail', compact('order'));
    }
    
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('orders.history')->with('success', 'Đơn hàng đã được hủy.');
    }
}