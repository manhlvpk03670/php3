@extends('layout.master')

@section('content')
    <style>
        * {
            /* font-family: 'Quicksand', sans-serif; */
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .toggle-form {
            cursor: pointer;
            color: #0d6efd;
        }

        .toggle-form:hover {
            text-decoration: underline;
        }
    </style>
    <div class="container">
        <div class="form-container" id="registerForm">
            <h2 class="text-center mb-4">Đăng Ký</h2>
            <form action="/register" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="registerUsername" class="form-label">Tên người dùng</label>
                    <input type="text" class="form-control" id="registerUsername" name="username"
                        placeholder="Nhập tên người dùng">
                    @error('username')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="registerEmail" name="email"
                        placeholder="Nhập email của bạn">
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="registerPassword" name="password"
                        placeholder="Nhập mật khẩu">
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                        placeholder="Xác nhận mật khẩu">
                    @error('confirm_password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success w-100">Đăng Ký</button>
                <div class="text-center mt-3">
                    <a href="/login" class="text-center mt-3 text-decoration-none text-muted">Đã có tài khoản? <span
                            class="toggle-form">Đăng nhập
                            ngay</span></a>
                </div>
            </form>
        </div>
    </div>
@endsection