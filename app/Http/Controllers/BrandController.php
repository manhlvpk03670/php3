<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('brands.create', compact('categories'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image',
            'description' => 'nullable|string'
        ]);
    
        Brand::create($request->all());
    
        return redirect()->route('brands.index')->with('success', 'Thêm thương hiệu ok');
    }
    

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $categories = Category::all();
        return view('brands.edit', compact('brand', 'categories'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image',
            'description' => 'nullable|string'
        ]);
    
        $brand = Brand::findOrFail($id);
        $brand->update($request->all());
    
        return redirect()->route('brands.index')->with('success', 'Cập nhật thương hiệu thành công');
    }
    

    public function destroy($id)
    {
        Brand::destroy($id);
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã xóa thành công');
    }
}

