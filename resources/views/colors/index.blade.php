@extends('layout.master')

@section('content')
<div class="container">
    <h2>Danh sách Màu sắc</h2>
    <a href="{{ route('colors.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>Thêm màu sắc</a>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Hành động</th>
        </tr>
        @foreach ($colors as $color)
        <tr>
            <td>{{ $color->id }}</td>
            <td>{{ $color->name }}</td>
            <td>
                <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning"> <i class="fa fa-edit"></i>Sửa</a>
                <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i>Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
