<!-- orders/index.blade.php -->
@extends('layout.master')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header ">
                <h2 class="h4 mb-0"><i class="fas fa-shopping-cart me-2"></i>Danh sách đơn hàng</h2>
            </div>

            <!-- Bộ lọc trạng thái -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-0">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <label for="status" class="form-label fw-bold me-3 mb-0">Lọc theo trạng thái:</label>
                                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Tất cả --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                                    </option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                        Đang xử lý</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn
                                        thành</option>
                                        <option value="paid" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn
                                            thành thanh toán banking</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã
                                        hủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Tìm kiếm theo mã đơn..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bảng danh sách đơn hàng -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Mã đơn</th>
                                <th>Người đặt</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-end pe-3">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-3 fw-bold">#{{ $order->id }}</td>
                                    <td>{{ $order->user->username ?? 'Khách' }}</td>
                                    <td>{{ number_format($order->total_price) }}đ</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            @if ($order->payment_method == 'cod')
                                                <i class="fas fa-money-bill-wave me-1"></i>
                                            @elseif($order->payment_method == 'banking')
                                                <i class="fas fa-university me-1"></i>
                                            @elseif($order->payment_method == 'momo')
                                                <i class="fas fa-wallet me-1"></i>
                                            @else
                                                <i class="fas fa-credit-card me-1"></i>
                                            @endif
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge 
                                    @if ($order->status == 'pending') bg-warning text-dark
                                    @elseif($order->status == 'processing') bg-info text-white
                                    @elseif($order->status == 'completed' || $order->status == 'shipped') bg-success text-white
                                    @elseif($order->status == 'cancelled') bg-danger text-white
                                    @else bg-secondary text-white @endif
                                    px-3 py-2">
                                            @if ($order->status == 'pending')
                                                <i class="fas fa-clock me-1"></i>
                                            @elseif($order->status == 'processing')
                                                <i class="fas fa-cog me-1"></i>
                                            @elseif($order->status == 'shipped')
                                                <i class="fas fa-truck me-1"></i>
                                            @elseif($order->status == 'completed')
                                                <i class="fas fa-check me-1"></i>
                                            @elseif($order->status == 'paid')
                                                <i class="fas fa-check me-1"></i>
                                            @elseif($order->status == 'cancelled')
                                                <i class="fas fa-times me-1"></i>
                                            @endif
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center py-3">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5>Không có đơn hàng nào.</h5>
                                            <p class="text-muted">Chưa có đơn hàng nào được tạo hoặc phù hợp với bộ lọc.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Phân trang -->
            @if ($orders->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .badge {
                font-size: 0.85rem;
                padding: 0.4em 0.65em;
            }

            .card {
                border-radius: 0.5rem;
                overflow: hidden;
            }

            .card-header {
                border-bottom: 0;
            }

            .table> :not(caption)>*>* {
                padding: 1rem 0.75rem;
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
