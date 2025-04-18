@extends('layout.master')
@section('title', 'Danh sách Thương hiệu')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách Thương hiệu</h2>
        <a href="{{ route('brands.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm Brand
        </a>
    </div>

    <table class="table table-bordered table-hover table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th scope="col">Tên</th>
                <th scope="col">Danh mục</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
                <tr>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->category?->name ?? 'Chưa có danh mục' }}</td>
                    <td>
                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
