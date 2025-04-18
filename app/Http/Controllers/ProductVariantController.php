<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::paginate(10); // Hiển thị 10 bản ghi mỗi trang
        return view('variants.index', compact('variants'));
    }

    public function create()
    {
        $products = Product::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('variants.create', compact('products', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        // Validation với kiểm tra trùng biến thể (cùng product_id, color_id, size_id)
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|unique:product_variants,sku',

            // Kiểm tra không được trùng màu sắc & kích thước cho cùng một sản phẩm
            'product_id' => [
                'required',
                Rule::exists('products', 'id'),
                Rule::unique('product_variants')->where(function ($query) use ($request) {
                    return $query->where('product_id', $request->product_id)
                                 ->where('color_id', $request->color_id)
                                 ->where('size_id', $request->size_id);
                }),
            ],
        ], [
            'sku.unique' => 'Mã SKU đã tồn tại.',
            'product_id.unique' => 'Biến thể này đã tồn tại. Hãy chọn màu sắc hoặc kích thước khác.',
        ]);

        // Xử lý hình ảnh nếu có
        $imagePath = $request->file('image') ? $request->file('image')->store('variants', 'public') : null;

        // Lưu vào database
        ProductVariant::create([
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id,
            'image' => $imagePath,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'sku' => $request->sku,
        ]);

        return redirect()->route('variants.index')->with('success', 'Biến thể sản phẩm đã được thêm.');
    }

    public function edit(ProductVariant $variant)
    {
        $products = Product::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('variants.edit', compact('variant', 'products', 'colors', 'sizes'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        // Validation cho cập nhật, bỏ qua SKU của chính nó
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:sizes,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|unique:product_variants,sku,' . $variant->id,

            // Kiểm tra không được trùng màu sắc & kích thước cho cùng một sản phẩm
            'product_id' => [
                'required',
                Rule::exists('products', 'id'),
                Rule::unique('product_variants')->where(function ($query) use ($request, $variant) {
                    return $query->where('product_id', $request->product_id)
                                 ->where('color_id', $request->color_id)
                                 ->where('size_id', $request->size_id)
                                 ->where('id', '!=', $variant->id); // Loại trừ chính nó khi cập nhật
                }),
            ],
        ], [
            'sku.unique' => 'Mã SKU đã tồn tại.',
            'product_id.unique' => 'Biến thể này đã tồn tại. Hãy chọn màu sắc hoặc kích thước khác.',
        ]);

        // Xử lý ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
        } else {
            $imagePath = $variant->image;
        }

        // Cập nhật dữ liệu
        $variant->update([
            'product_id' => $request->product_id,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id,
            'image' => $imagePath,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'sku' => $request->sku,
        ]);

        return redirect()->route('variants.index')->with('success', 'Biến thể sản phẩm đã được cập nhật.');
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->route('variants.index')->with('success', 'Biến thể sản phẩm đã được xóa.');
    }
}
