<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductReviewController;


// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Categories (Chỉ Admin có quyền quản lý)
Route::middleware(['auth', 'admin'])->group(function () {
    // Thay đổi từ một route đơn lẻ thành resource route
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class)->except(['show']); // Chặn user xem chi tiết sản phẩm

    // Brands
    Route::resource('brands', BrandController::class);

    // Users
    Route::resource('users', UserController::class);

    // Sizes vs Colors
    Route::resource('sizes', SizeController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('variants', ProductVariantController::class);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

});
Route::middleware(['auth', 'admin'])->group(function () {
    // Thêm route với tên cho từng hành động
    Route::resource('coupons', CouponController::class)->names([
        'index' => 'admin.coupons.index',
        'create' => 'admin.coupons.create',
        'store' => 'admin.coupons.store',
        'edit' => 'admin.coupons.edit',
        'update' => 'admin.coupons.update',
        'destroy' => 'admin.coupons.destroy',
    ]);
});
// Add these routes to your web.php file
Route::middleware(['auth'])->group(function () {
    // Existing routes
    // New coupon routes
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
});

// Products cho người dùng
Route::get('/products-for-user', [ProductController::class, 'userProducts'])->name('user.products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/quen-mat-khau', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/quen-mat-khau', [UserController::class, 'sendResetLink'])->name('password.send-link');
Route::get('/xac-nhan-otp', [UserController::class, 'verifyOtp'])->name('password.verify-otp');
Route::post('/xac-nhan-otp', [UserController::class, 'validateOtp'])->name('password.validate-otp');
Route::get('/dat-lai-mat-khau', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/dat-lai-mat-khau', [UserController::class, 'resetPassword'])->name('password.update');
// Login & Register
Route::get('/login', function () {
    $title = "Đăng nhập";
    return view('auth.login', compact('title'));
})->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::get('/logout', [UserController::class, 'logout']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/register', function () {
    $title = "Đăng ký";
    return view('auth.register', compact('title'));
});

// Đăng xuất
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Profile (Chỉ người dùng đã đăng nhập mới có thể truy cập)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); // Hiển thị giỏ hàng
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add'); // Thêm sản phẩm vào giỏ
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout'); // Điều hướng đến trang thanh toán
});
//eror
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Product Review Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ProductReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [ProductReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ProductReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');
});

//dang nhap voi google va facebook
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);


Route::middleware('auth')->group(function () {
    Route::post('/checkout/cod', [CheckoutController::class, 'codPayment'])->name('checkout.cod');
    Route::post('/checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process'); // Thêm cho các phương thức thanh toán khác
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('orders.success');

    // Các route cho VNPAY, MOMO (nếu cần thiết)
// web.php
Route::get('/checkout/vnpay/{orderCode}', [CheckoutController::class, 'checkoutVnpay'])->name('checkout.vnpay');
    Route::get('/payment/callback', [CheckoutController::class, 'vnpayCallback'])->name('payment.callback');
    Route::get('/checkout/momo', [CheckoutController::class, 'momo'])->name('checkout.momo');
    
});
// Route cho trang checkout
Route::middleware('auth')->get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

Route::middleware(['auth'])->group(function () {
    Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    Route::get('/orders/{order}', [CheckoutController::class, 'orderDetail'])->name('orders.detail');
});
Route::put('/orders/{order}/cancel', [CheckoutController::class, 'cancel'])->name('orders.cancel');
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // 🆕 Quản lý đơn hàng cho admin - dùng prefix riêng để tránh trùng
    Route::get('/manage-orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders.index');
    Route::get('/manage-orders/{id}', [App\Http\Controllers\AdminController::class, 'orderDetail'])->name('admin.orders.show');
    Route::put('/manage-orders/{id}', [App\Http\Controllers\AdminController::class, 'updateOrderStatus'])->name('admin.orders.update');
});
