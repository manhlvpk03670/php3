<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_details' => 'required|array',
            'payment_method' => 'required|string',
            'coupon_code' => 'nullable|string',
        ]);

        $total = 0;
        foreach ($validated['order_details'] as $detail) {
            $total += $detail['price'] * $detail['quantity'];
        }

        // Áp mã giảm giá
        $discount = 0;
        $couponCode = $validated['coupon_code'] ?? null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid() && $total >= $coupon->min_order_value) {
                $discount = $coupon->discount_percent
                    ? $total * $coupon->discount_percent / 100
                    : $coupon->discount_amount;
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total - $discount,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'coupon_code' => $couponCode,
            'discount_amount' => $discount,
        ]);

        foreach ($validated['order_details'] as $detail) {
            $order->orderDetails()->create($detail);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ]);
    }
}
