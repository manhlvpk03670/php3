<!-- orders/detail.blade.php -->
@extends('layout.master')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Back button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('orders.history') }}">Lịch sử đơn hàng</a></li>
                <li class="breadcrumb-item active">Chi tiết đơn hàng #{{ $order->id }}</li>
            </ol>
        </nav>
        <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <!-- Order Status Timeline -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title d-flex align-items-center mb-3">
                <i class="fas fa-shopping-bag me-2 text-primary"></i>
                Chi tiết đơn hàng #{{ $order->id }}
                <span class="ms-auto badge 
                    @if($order->status == 'pending') bg-warning text-dark
                    @elseif($order->status == 'processing') bg-info text-white
                    @elseif($order->status == 'completed') bg-success text-white
                                        @elseif($order->status == 'paid') bg-success text-white

                    @elseif($order->status == 'shipped') bg-primary text-white
                    @elseif($order->status == 'cancelled') bg-danger text-white
                    @else bg-secondary text-white @endif
                    px-3 py-2">
                    @if($order->status == 'pending')
                        <i class="fas fa-clock me-1"></i> Chờ xử lý
                    @elseif($order->status == 'processing')
                        <i class="fas fa-cog fa-spin me-1"></i> Đang xử lý
                    @elseif($order->status == 'shipped')
                        <i class="fas fa-truck me-1"></i> Đang giao hàng
                    @elseif($order->status == 'completed')
                        <i class="fas fa-check-circle me-1"></i> Hoàn thành
                        @elseif($order->status == 'paid')
                        <i class="fas fa-check-circle me-1"></i> Hoàn thành thanh toán banking
                    @elseif($order->status == 'cancelled')
                        <i class="fas fa-times-circle me-1"></i> Đã hủy
                    @endif
                </span>
            </h4>

            <!-- Order Timeline -->
            <div class="position-relative my-4 px-4">
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 
                        @if($order->status == 'pending') 25%
                        @elseif($order->status == 'processing') 50%
                        @elseif($order->status == 'shipped') 75%
                        @elseif($order->status == 'completed') 100%
                        @elseif($order->status == 'paid') 100%
                        @else 0% @endif" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="text-center position-absolute" style="left: 0%; margin-top: 10px;">
                        <div class="rounded-circle 
                            @if($order->status != 'cancelled') bg-primary @else bg-secondary @endif
                            text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                            style="width: 30px; height: 30px; margin-top: -43px;">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <small class="d-block text-nowrap">Đặt hàng</small>
                    </div>
                    <div class="text-center position-absolute" style="left: 33%; margin-top: 10px;">
                        <div class="rounded-circle 
                            @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed') bg-primary @else bg-secondary @endif
                            text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                            style="width: 30px; height: 30px; margin-top: -43px;">
                            <i class="fas fa-box"></i>
                        </div>
                        <small class="d-block text-nowrap">Đóng gói</small>
                    </div>
                    <div class="text-center position-absolute" style="left: 66%; margin-top: 10px;">
                        <div class="rounded-circle 
                            @if($order->status == 'shipped' || $order->status == 'completed') bg-primary @else bg-secondary @endif
                            text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                            style="width: 30px; height: 30px; margin-top: -43px;">
                            <i class="fas fa-truck"></i>
                        </div>
                        <small class="d-block text-nowrap">Vận chuyển</small>
                    </div>
                    <div class="text-center position-absolute" style="left: 100%; margin-top: 10px;">
                        <div class="rounded-circle 
                            @if($order->status == 'completed') bg-primary @else bg-secondary @endif
                            text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                            style="width: 30px; height: 30px; margin-top: -43px; transform: translateX(-100%);">
                            <i class="fas fa-check"></i>
                        </div>
                        <small class="d-block text-nowrap" style="transform: translateX(-100%);">Hoàn thành</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="fw-medium">Ngày đặt:</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="fw-medium">Trạng thái:</span>
                            <span class="badge 
                                @if($order->status == 'pending') bg-warning text-dark
                                @elseif($order->status == 'processing') bg-info text-white
                                @elseif($order->status == 'completed') bg-success text-white
                                    @elseif($order->status == 'paid') bg-success text-white
                                @elseif($order->status == 'shipped') bg-primary text-white
                                @elseif($order->status == 'cancelled') bg-danger text-white
                                @else bg-secondary text-white @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="fw-medium">Phương thức thanh toán:</span>
                            <span>{{ $order->payment_method ?? 'COD' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="fw-medium">Tổng tiền:</span>
                            <span class="fw-bold text-primary">{{ number_format($order->total_price) }}₫</span>
                        </li>
                    </ul>
                </div>
                
                @if (in_array($order->status, ['pending', 'processing']))
                <div class="card-footer bg-white">
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-ban me-1"></i> Hủy đơn hàng
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <div class="form-control bg-light">
                                    <small class="text-muted d-block">Người nhận</small>
                                    <div>{{ $order->orderDetails->first()->recipient_name ?? 'Chưa cung cấp' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <div class="form-control bg-light">
                                    <small class="text-muted d-block">Số điện thoại</small>
                                    <div>{{ $order->orderDetails->first()->recipient_phone ?? 'Chưa cung cấp' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-map-marked-alt"></i></span>
                                <div class="form-control bg-light">
                                    <small class="text-muted d-block">Địa chỉ giao hàng</small>
                                    <div>{{ $order->orderDetails->first()->recipient_address ?? 'Chưa cung cấp' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Sản phẩm đã đặt</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Thông tin</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $detail)
                            <tr>
                                <td style="width: 80px;">
                                    @if ($detail->productVariant->product->image)
                                        <img src="{{ asset('storage/' . $detail->productVariant->product->image) }}" 
                                            alt="{{ $detail->productVariant->product->name }}" 
                                            class="img-thumbnail" width="80" height="80">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-1">{{ $detail->productVariant->product->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        @if($detail->productVariant->color->name ?? false)
                                            <span class="me-2">Màu: {{ $detail->productVariant->color->name }}</span>
                                        @endif
                                        @if($detail->productVariant->size->name ?? false)
                                            <span>Size: {{ $detail->productVariant->size->name }}</span>
                                        @endif
                                    </small>
                                </td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">{{ number_format($detail->price) }}₫</td>
                                <td class="text-end fw-bold">{{ number_format($detail->subtotal) }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-end fw-bold fs-5 text-primary">{{ number_format($order->total_price) }}₫</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
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
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        object-fit: cover;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    .fw-medium {
        font-weight: 500;
    }
    .input-group-text {
        border: none;
    }
    .form-control.bg-light {
        border: none;
    }
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
    }
    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
    }
    .table > :not(caption) > * > * {
        padding: 0.75rem;
        vertical-align: middle;
    }
</style>
@endpush
@endsection