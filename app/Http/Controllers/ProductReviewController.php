<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class ProductReviewController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
    
        // Lấy các đánh giá của sản phẩm và kèm theo thông tin người dùng
        $reviews = ProductReview::where('product_id', $product->id)
                                ->with('user') // Để lấy thông tin người dùng
                                ->latest() // Sắp xếp theo thời gian đánh giá mới nhất
                                ->get();
    
        // Truyền cả sản phẩm và đánh giá vào view
        return view('productdetail', compact('product', 'reviews'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:products,id',
        ]);
    
        ProductReview::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
    
        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công.');
    }
    
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);

        if (auth()->user()->id !== $review->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Đánh giá đã được xóa.');
    }

    public function edit($id)
    {
        $review = ProductReview::findOrFail($id);

        if (auth()->user()->id !== $review->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền sửa đánh giá này.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = ProductReview::findOrFail($id);

        if (auth()->user()->id !== $review->user_id && auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền sửa đánh giá này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Đánh giá đã được cập nhật.');
    }
}
