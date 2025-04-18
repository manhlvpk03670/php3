<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductReview; // nhớ thêm dòng này đầu controller nếu chưa có

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('brand')->get();
        return view('products.index', compact('products'));
    }
    public function show($id)
    {
        $product = Product::with(['brand', 'category', 'variants'])->find($id);

        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        // Lấy các sản phẩm liên quan (cùng danh mục hoặc cùng thương hiệu)
        $relatedProducts = Product::with('brand', 'category')
            ->where('id', '!=', $product->id)  // Loại trừ sản phẩm hiện tại
            ->where(function ($query) use ($product) {
                // Tìm sản phẩm cùng danh mục hoặc cùng thương hiệu
                $query->where('category_id', $product->category_id)
                    ->orWhere('brand_id', $product->brand_id);
            })
            ->limit(4)  // Giới hạn số lượng sản phẩm liên quan
            ->get();

        // Lấy các đánh giá của sản phẩm
        $reviews = ProductReview::where('product_id', $product->id)
            ->with('user')
            ->latest()
            ->get();

        return view('productdetail', compact('product', 'reviews', 'relatedProducts'));
    }



    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }


    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = Category::all();
        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function userProducts(Request $request)
    {
        $query = Product::query()->with('brand', 'category');

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $brandIds = Brand::where('category_id', $request->category)->pluck('id')->toArray();

            if ($request->filled('brand')) {
                // Nếu chọn cả danh mục & thương hiệu, chỉ lấy thương hiệu đó trong danh mục
                $query->where('brand_id', $request->brand);
            } else {
                // Nếu chỉ chọn danh mục, lấy tất cả sản phẩm thuộc các thương hiệu trong danh mục
                $query->whereIn('brand_id', $brandIds);
            }
        } elseif ($request->filled('brand')) {
            // Lọc theo thương hiệu nếu chỉ có brand
            $query->where('brand_id', $request->brand);
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Phân trang (4 sản phẩm mỗi trang)
        $products = $query->paginate(4)->withQueryString();

        // Lấy danh sách thương hiệu theo danh mục hiện tại (nếu có)
        $brands = $request->filled('category')
            ? Brand::where('category_id', $request->category)->get()
            : Brand::all();

        $categories = Category::all();

        return view('products.products', compact('products', 'brands', 'categories'));
    }
}
