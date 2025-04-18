@extends('layout.master')

@section('content')
<div class="container">
    <h2>Thông tin cá nhân</h2>
    


    <form action="{{ url('/profile/update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tên người dùng</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu mới (nếu muốn thay đổi)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
