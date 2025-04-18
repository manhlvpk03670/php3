@extends('layout.master')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Chỉnh sửa sản phẩm</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm:</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá:</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng:</label>
            <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục:</label>
            <select name="category_id" id="category_id" class="form-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="brand_id" class="form-label">Thương hiệu:</label>
            <select name="brand_id" id="brand_id" class="form-select" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" data-category="{{ $brand->category_id }}" 
                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const categorySelect = document.getElementById("category_id");
            const brandSelect = document.getElementById("brand_id");
        
            function filterBrands() {
                const selectedCategory = categorySelect.value;
                Array.from(brandSelect.options).forEach(option => {
                    option.style.display = option.getAttribute("data-category") == selectedCategory ? "block" : "none";
                });
        
                // Tự động chọn thương hiệu đầu tiên của danh mục được chọn (nếu có)
                const visibleBrands = Array.from(brandSelect.options).filter(option => option.style.display === "block");
                if (visibleBrands.length > 0) {
                    visibleBrands[0].selected = true;
                }
            }
        
            categorySelect.addEventListener("change", filterBrands);
            filterBrands(); // Chạy ngay khi load trang để hiển thị đúng brand theo category
        });
        </script>
        

        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="100">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
