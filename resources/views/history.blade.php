@extends('layout.master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Lịch sử đơn hàng</h2>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.history') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>banking</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date_from" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4">
                    <label for="date_to" class="form-label">Đến ngày</label>
                    <div class="d-flex">
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($orders->count() > 0)
        <!-- Order List -->
        <div class="row">
            @foreach($orders as $order)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Đơn hàng #{{ $order->id }}</h5>
                        <span class="badge 
                            @if($order->status == 'pending') bg-warning text-dark
                            @elseif($order->status == 'processing') bg-info text-white
                            @elseif($order->status == 'shipped') bg-primary text-white
                            @elseif($order->status == 'completed') bg-success text-white
                                                        @elseif($order->status == 'paid') bg-success text-white

                            @elseif($order->status == 'cancelled') bg-danger text-white
                            @else bg-secondary text-white @endif">
                            @if($order->status == 'pending')
                                <i class="fas fa-clock me-1"></i> Chờ xử lý
                            @elseif($order->status == 'processing')
                                <i class="fas fa-cog fa-spin me-1"></i> Đang xử lý
                            @elseif($order->status == 'shipped')
                                <i class="fas fa-truck me-1"></i> Đang giao hàng
                            @elseif($order->status == 'completed')
                                <i class="fas fa-check-circle me-1"></i> Hoàn thành
                                @elseif($order->status == 'paid')
                                <i class="fas fa-check-circle me-1"></i> Thanh toán đơn hàng banking
                            @elseif($order->status == 'cancelled')
                                <i class="fas fa-times-circle me-1"></i> Đã hủy
                            @endif
                        </span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span><i class="far fa-calendar-alt me-2 text-muted"></i>Ngày đặt:</span>
                                <span>{{ $order->created_at->format('d/m/Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span><i class="fas fa-box me-2 text-muted"></i>Số sản phẩm:</span>
                                <span>{{ $order->orderDetails->sum('quantity') }} sản phẩm</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span><i class="fas fa-money-bill-wave me-2 text-muted"></i>Tổng tiền:</span>
                                <span class="fw-bold">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                            </li>
                        </ul>
                        
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
                                @if($order->status == 'pending') 25%
                                @elseif($order->status == 'processing') 50%
                                @elseif($order->status == 'shipped') 75%
                                @elseif($order->status == 'completed') 100%
                                                                @elseif($order->status == 'paid') 100%

                                @else 0% @endif" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-white" style="width: 250px;">
                        <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-warning w-100">
                            <i class="fas fa-eye me-1" ></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


    @else
        <!-- Empty State -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <div class="py-4">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p class="text-muted">Hãy mua sắm ngay để trải nghiệm dịch vụ của chúng tôi!</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-shopping-cart me-1"></i> Mua sắm ngay
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.65em;
        font-weight: 500;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    .progress {
        border-radius: 50px;
        background-color: #e9ecef;
    }
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        color: #4e73df;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        margin: 0 0.2rem;
    }
    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>
@endpush
@endsection 