@extends('layout.master')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="order-success">
        <div class="container">
            <h1>Đặt hàng thành công!</h1>
            <p>Cảm ơn bạn đã mua hàng tại cửa hàng chúng tôi. Đơn hàng của bạn đang được xử lý.</p>
            <a href="{{ route('home') }}" class="btn-back-home">Quay lại trang chủ</a>
        </div>
    </div>
@endsection

<style>
    /* CSS cho trang success */
.order-success {
    background-color: #f9f9f9;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 0;
}

.order-success .container {
    background-color: #ffffff;
    padding: 40px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    max-width: 500px;
    width: 100%;
}

.order-success h1 {
    font-size: 2.5rem;
    color: #28a745;
    margin-bottom: 20px;
    font-weight: bold;
}

.order-success p {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 30px;
}

.order-success .btn-back-home {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.order-success .btn-back-home:hover {
    background-color: #0056b3;
}

</style>