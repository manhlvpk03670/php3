<!-- orders/show.blade.php -->
@extends('layout.master')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn #{{ $order->id }}</li>
            </ol>
        </nav>
        <div>
            @if($order->status != 'cancelled')
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="fas fa-ban me-1"></i> Hủy đơn
                </button>
            @endif
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin đơn hàng & Trạng thái -->
    <div class="row">
        <!-- Thông tin chung -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng #{{ $order->id }}</h5>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning text-dark
                        @elseif($order->status == 'processing') bg-info text-white
                        @elseif($order->status == 'completed' || $order->status == 'shipped') bg-success text-white
                        @elseif($order->status == 'cancelled') bg-danger text-white
                        @else bg-secondary text-white @endif
                        px-3 py-2">
                        @if($order->status == 'pending')
                            <i class="fas fa-clock me-1"></i>
                        @elseif($order->status == 'processing')
                            <i class="fas fa-cog me-1"></i>
                        @elseif($order->status == 'shipped')
                            <i class="fas fa-truck me-1"></i>
                        @elseif($order->status == 'completed')
                            <i class="fas fa-check me-1"></i>
                        @elseif($order->status == 'cancelled')
                            <i class="fas fa-times me-1"></i>
                        @endif
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Thông tin khách hàng -->
                        <div class="col-md-6 mb-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
                                <p class="mb-2"><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách' }}</p>
                                <p class="mb-2"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                                <p class="mb-2"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-0"><strong>Phương thức:</strong> {{ ucfirst($order->payment_method) }}</p>
                            </div>
                        </div>
                        
                        <!-- Thông tin người nhận -->
                        <div class="col-md-6 mb-4">
                            <div class="p-3 bg-light rounded">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-map-marker-alt me-2"></i>Thông tin giao hàng</h6>
                                <p class="mb-2"><strong>Người nhận:</strong> {{ $order->orderDetails->first()->recipient_name ?? 'Không rõ' }}</p>
                                <p class="mb-2"><strong>SĐT:</strong> {{ $order->orderDetails->first()->recipient_phone ?? 'Không rõ' }}</p>
                                <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->orderDetails->first()->recipient_address ?? 'Không rõ' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tiến trình đơn hàng -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-tasks me-2"></i>Tiến trình đơn hàng</h6>
                        <div class="position-relative my-4 px-4">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 
                                    @if($order->status == 'pending') 25%
                                    @elseif($order->status == 'processing') 50%
                                    @elseif($order->status == 'shipped') 75%
                                    @elseif($order->status == 'completed') 100%
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
                                    <small class="d-block text-nowrap">Chờ xử lý</small>
                                </div>
                                <div class="text-center position-absolute" style="left: 33%; margin-top: 10px;">
                                    <div class="rounded-circle 
                                        @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'completed') bg-primary @else bg-secondary @endif
                                        text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                                        style="width: 30px; height: 30px; margin-top: -43px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <small class="d-block text-nowrap">Đang xử lý</small>
                                </div>
                                <div class="text-center position-absolute" style="left: 66%; margin-top: 10px;">
                                    <div class="rounded-circle 
                                        @if($order->status == 'shipped' || $order->status == 'completed') bg-primary @else bg-secondary @endif
                                        text-white d-flex align-items-center justify-content-center mx-auto mb-1" 
                                        style="width: 30px; height: 30px; margin-top: -43px;">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <small class="d-block text-nowrap">Đang giao</small>
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
            </div>
        </div>
        
        <!-- Cập nhật trạng thái -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Cập nhật trạng thái</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select form-select-lg mb-3" id="status" {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">Ghi chú</label>
                            <textarea class="form-control" id="note" name="note" rows="3" placeholder="Thêm ghi chú cho đơn hàng..." {{ $order->status == 'cancelled' ? 'disabled' : '' }}></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100" {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Sản phẩm trong đơn</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th class="text-end pe-3">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderDetails as $item)
                            <tr>
                                <td style="width: 120px;">
                                    @if (!empty($item->productVariant->product->image))
                                        <img src="{{ asset('storage/' . $item->productVariant->product->image) }}" 
                                            alt="Ảnh sản phẩm" class="img-thumbnail" style="max-width: 100px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                            style="width: 100px; height: 100px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-1">{{ $item->productVariant->product->name ?? 'Không rõ' }}</h6>
                                    @if(!empty($item->productVariant->attributes))
                                        <small class="text-muted">
                                            @foreach($item->productVariant->attributes as $attribute)
                                                {{ $attribute->name }}: {{ $attribute->value }}
                                                @if(!$loop->last) | @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </td>
                                <td>{{ number_format($item->price) }}đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end pe-3 fw-bold">{{ number_format($item->subtotal) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-end pe-3 fw-bold fs-5 text-primary">{{ number_format($order->total_price) }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận hủy đơn -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng #{{ $order->id }}?</p>
                <p>Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        border: none;
    }
    .card-header {
        border-bottom: 0;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.65em;
    }
    .progress {
        background-color: #e9ecef;
    }
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .table > :not(caption) > * > * {
        padding: 0.75rem;
    }
</style>
@endpush
@endsection