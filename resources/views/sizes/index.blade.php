@extends('layout.master')

@section('content')
<div class="container">
    <h2>Danh sách Size</h2>
    <a href="{{ route('sizes.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm Size</a>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Hành động</th>
        </tr>
        @foreach ($sizes as $size)
        <tr>
            <td>{{ $size->id }}</td>
            <td>{{ $size->name }}</td>
            <td>
                <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i>Sửa</a>
                <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
