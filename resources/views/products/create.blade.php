@extends('layout.master')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá:</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng:</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục:</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Chọn danh mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="brand_id" class="form-label">Thương hiệu:</label>
            <select name="brand_id" id="brand_id" class="form-control" required>
                <option value="">Chọn thương hiệu</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" data-category="{{ $brand->category_id }}">
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả:</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="discount" class="form-label">Giảm giá (%):</label>
            <input type="number" name="discount" class="form-control" step="0.01" min="0" max="100">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh:</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </form>
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

        // Chọn thương hiệu đầu tiên của danh mục nếu có
        const visibleBrands = Array.from(brandSelect.options).filter(option => option.style.display === "block");
        if (visibleBrands.length > 0) {
            visibleBrands[0].selected = true;
        } else {
            brandSelect.value = ""; // Không có thương hiệu phù hợp thì reset chọn
        }
    }

    categorySelect.addEventListener("change", filterBrands);
    filterBrands(); // Chạy ngay khi load trang để hiển thị đúng brand theo category
});
</script>

@endsection
