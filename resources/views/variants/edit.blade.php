@extends('layout.master')

@section('content')
<div class="container">
    <h1 class="my-4">Chỉnh sửa biến thể</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Sản phẩm</label>
            <select name="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $variant->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Màu sắc</label>
            <select name="color_id" class="form-control">
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kích thước</label>
            <select name="size_id" class="form-control">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ $variant->sku }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $variant->price }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="quantity" class="form-control" value="{{ $variant->quantity }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
            @if($variant->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $variant->image) }}" width="80" class="img-thumbnail">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('variants.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
