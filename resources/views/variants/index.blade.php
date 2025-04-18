@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Danh sách biến thể</h1>
        <a href="{{ route('variants.create') }}" class="btn btn-primary mb-3">Thêm biến thể</a>

        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Màu sắc</th>
                    <th>Kích thước</th>
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variants as $variant)
                    <tr>
                        <td>{{ $variant->product->name ?? 'N/A' }}</td>
                        <td>
                            @if($variant->image)
                                <img src="{{ asset('storage/' . $variant->image) }}" width="50">
                            @else
                                Không có ảnh
                            @endif
                        </td>
                        <td>{{ $variant->color->name ?? 'N/A' }}</td>
                        <td>{{ $variant->size->name ?? 'N/A' }}</td>
                        <td>{{ $variant->sku }}</td>
                        <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                        <td>{{ $variant->quantity }}</td>

                        <td>
                            <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Thêm phần phân trang -->
        <div class="d-flex justify-content-center">
            {{ $variants->links() }}
        </div>
    </div>
@endsection
