@extends('layout.master')

@section('title', 'Trang nầy không tìm thấy')
<style>
    .error-container {
        padding: 80px 0;
        text-align: center;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #f8f9fa;
    }
    
    .error-code {
        font-size: 120px;
        font-weight: 700;
        color: #ff6b6b;
        margin-bottom: 0;
        text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        letter-spacing: 5px;
    }
    
    .error-title {
        font-size: 28px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
    }
    
    .error-message {
        font-size: 18px;
        color: #6c757d;
        margin-bottom: 40px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .home-button {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(45deg, #4e73df, #36b9cc);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .home-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .error-image {
        max-width: 300px;
        margin: 0 auto 30px;
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-15px);
        }
        100% {
            transform: translateY(0px);
        }
    }
</style>


@section('content')
<div class="error-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="error-image animate-float">
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 180C144.183 180 180 144.183 180 100C180 55.8172 144.183 20 100 20C55.8172 20 20 55.8172 20 100C20 144.183 55.8172 180 100 180Z" fill="#f8f9fa" stroke="#e9ecef" stroke-width="2"/>
                        <path d="M130 75C130 83.2843 123.284 90 115 90C106.716 90 100 83.2843 100 75C100 66.7157 106.716 60 115 60C123.284 60 130 66.7157 130 75Z" fill="#ff6b6b"/>
                        <path d="M100 75C100 83.2843 93.2843 90 85 90C76.7157 90 70 83.2843 70 75C70 66.7157 76.7157 60 85 60C93.2843 60 100 66.7157 100 75Z" fill="#ff6b6b"/>
                        <path d="M65 135C65 135 75 120 100 120C125 120 135 135 135 135" stroke="#495057" stroke-width="6" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="error-code">404</h1>
                <h2 class="error-title">Không tìm thấy trang</h2>
                <p class="error-message">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa. Có thể URL đã thay đổi hoặc trang đã bị gỡ bỏ.</p>
                <a href="{{ url('/') }}" class="home-button">
                    <i class="fas fa-home mr-2"></i> Quay về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
@endsection