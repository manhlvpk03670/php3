@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Xác Nhận Mã OTP</div>
                <div class="card-body">
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

                    <form method="POST" action="{{ route('password.validate-otp') }}">
                        @csrf
                        <div class="form-group">
                            <label for="otp">Mã OTP</label>
                            <input type="text" 
                                   class="form-control @error('otp') is-invalid @enderror" 
                                   id="otp" 
                                   name="otp" 
                                   required 
                                   maxlength="6" 
                                   pattern="\d{6}" 
                                   placeholder="Nhập 6 chữ số"
                                   autofocus>
                            @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                Xác Nhận
                            </button>
                            <a href="{{ route('password.forgot') }}" class="btn btn-link">
                                Gửi lại mã OTP
                            </a>
                        </div>
                    </form>

                    <div class="mt-3 text-muted">
                        <small>
                            * Mã OTP có hiệu lực trong 15 phút
                            * Mã chỉ được sử dụng một lần
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection