@extends('layout.master')

@section('title', '403 éc éc')
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
        color: #dc3545;
        margin-bottom: 0;
        text-shadow: 2px 2px 5px rgba(0,0,0,0.1);
    }
    
    .error-message {
        font-size: 24px;
        font-weight: 500;
        color: #343a40;
        margin-bottom: 30px;
    }
    
    .home-button {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(45deg, #007bff, #00bcd4);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .home-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .error-icon {
        font-size: 100px;
        margin-bottom: 20px;
        color: #dc3545;
    }
    
    .animate-pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

@section('content')
<div class="error-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <i class="fas fa-lock error-icon animate-pulse"></i>
                <h1 class="error-code">403</h1>
                <h2 class="error-message">Bạn không có quyền truy cập trang này</h2>
                <p class="mb-4">Hệ thống đã chặn yêu cầu của bạn. Vui lòng liên hệ quản trị viên nếu bạn cần hỗ trợ.</p>
                <a href="/" class="home-button">
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