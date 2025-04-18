<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;
use DB;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
class AdminController extends Controller
{

    public function dashboard()
    {
        // 1. Tổng số người dùng
        $totalUsers = User::count();

        // 2. Tổng doanh thu (đã thanh toán)
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        // 3. Tổng số sản phẩm
        $totalProducts = Product::count();

        // 4. Tổng tồn kho
        $totalStock = Product::sum('quantity');

        // 5. 5 đơn hàng gần nhất
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        // 6. 5 người dùng mới nhất
        $latestUsers = User::latest()->take(5)->get();

        // 7. Số người dùng theo tháng
        $userChart = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        // 8. Doanh thu theo tháng
        $revenueChart = Order::where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRevenue',
            'totalProducts',
            'totalStock',
            'latestOrders',
            'latestUsers',
            'userChart',
            'revenueChart'
        ));
    }
    public function orders(Request $request)
    {
        $query = Order::with('user')->orderByDesc('created_at');

        // Nếu có lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(5)->appends($request->all()); // mỗi trang 5 đơn hàng

        return view('admin.manage-orders.index', compact('orders'));
    }


    // 📦 Xem chi tiết đơn hàng
    public function orderDetail($id)
    {
        $order = Order::with(['orderDetails.productVariant.product', 'user'])->findOrFail($id);
        return view('admin.manage-orders.show', compact('order'));
    }

    // 🔄 Cập nhật trạng thái đơn hàng
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);
    
        $order = Order::with(['orderDetails.productVariant.product', 'user'])->findOrFail($id);
        $order->status = $request->status;
        $order->save();
    
        // Gửi email cho người dùng
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
    
        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công và đã gửi email cho khách hàng!');
    }
}
