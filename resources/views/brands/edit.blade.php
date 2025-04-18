@extends('layout.master')

@section('title', 'Chỉnh sửa Thương hiệu')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h2 class="text-center mb-4">Chỉnh sửa Thương hiệu</h2>

        <form action="{{ route('brands.update', $brand->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Tên Brand -->
            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Tên Brand:</label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="{{ old('name', $brand->name) }}" required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Danh mục -->
            <div class="mb-3">
                <label for="category_id" class="form-label fw-bold">Danh mục:</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ $brand->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nút hành động -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
