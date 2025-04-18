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

        .social-btn {
            font-size: 16px;
            padding: 10px;
        }

        .btn-facebook {
            background-color: #3b5998;
            color: white;
        }

        .btn-facebook:hover {
            background-color: #ffffff;
            color: #3b5998;
            border: 1px solid #3b5998;
        }

        .btn-google {
            background-color: #db4437;
            color: white;
        }

        .btn-google:hover {
            background-color: #ffffff;
            color: #db4437;
            border: 1px solid #db4437;
        }
    </style>

    <div class="container">
        <div class="form-container" id="loginForm">
            <h2 class="text-center mb-4">Đăng Nhập</h2>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <form method="POST" action="/login">
                @csrf
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="loginEmail"
                        placeholder="Nhập email của bạn">
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" id="loginPassword"
                        placeholder="Nhập mật khẩu">
                    @error('password')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
                    <div class="mb-3 text-end">
                        <a href="{{ route('password.forgot') }}" class="text-decoration-none">Quên mật khẩu?</a>
                    </div>
                </div>
                {{-- <a href="{{ route('password.request') }}" class="text-decoration-none">Quên mật khẩu?</a> --}}
                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
            </form>

            <!-- Đăng nhập bằng mạng xã hội -->
            <div class="text-center mt-3">
                <p>Hoặc đăng nhập bằng:</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="/auth/facebook" class="btn btn-facebook social-btn w-100">
                        <i class="bi bi-facebook me-2"></i> Facebook
                    </a>
                    <a href="/auth/google" class="btn btn-google social-btn w-100">
                        <i class="bi bi-google me-2"></i> Google
                    </a>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="/register" class="text-center mt-3 text-decoration-none text-muted">Chưa có tài khoản?
                    <span class="toggle-form">Đăng ký ngay</span>
                </a>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('#loginForm form');
            const emailInput = document.querySelector('#loginEmail');
            const passwordInput = document.querySelector('#loginPassword');
            const rememberMeCheckbox = document.querySelector('#rememberMe');

            if (localStorage.getItem('rememberMe') === 'true') {
                emailInput.value = localStorage.getItem('email') || '';
                passwordInput.value = localStorage.getItem('password') || '';
                rememberMeCheckbox.checked = true;
            }

            loginForm.addEventListener('submit', function(e) {
                if (rememberMeCheckbox.checked) {
                    localStorage.setItem('email', emailInput.value);
                    localStorage.setItem('password', passwordInput.value);
                    localStorage.setItem('rememberMe', 'true');
                } else {
                    localStorage.removeItem('email');
                    localStorage.removeItem('password');
                    localStorage.removeItem('rememberMe');
                }
            });
        });
    </script>
@endsection
