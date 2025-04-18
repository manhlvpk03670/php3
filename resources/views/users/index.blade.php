@extends('layout.master')
@section('title', 'Danh sách Người dùng')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách Người dùng</h2>

    </div>


    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">Tên Người dùng</th>
                <th scope="col">Email</th>
                <th scope="col">Vai trò</th>
                <th scope="col">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        @if ($user->role === 'admin')
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-ban"></i> Không thể xóa Admin
                        </button>
                    @else
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    @endif
                    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection