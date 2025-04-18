@extends('layout.master')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold text-danger"> Khám Phá Sản Phẩm Tuyệt Vời </h2>
    
    <!-- Form tìm kiếm và lọc -->
    <div class="card mb-4 border-0 shadow-sm rounded-3 bg-light">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('user.products') }}">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-danger"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm yêu thích...">
                        </div>
                    </div>

                    <!-- Lọc theo danh mục -->
                    <div class="col-md-3">
                        <select name="category" class="form-select border" id="category-select">
                            <option value=""> Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo thương hiệu -->
                    <div class="col-md-3">
                        <select name="brand" class="form-select border">
                            <option value=""> Tất cả thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="fa fa-sliders-h me-1"></i> Thêm
                        </button>
                    </div>
                    
                    <!-- Lọc nâng cao (có thể mở rộng) -->
                    <div class="col-12 collapse" id="advancedFilters">
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Khoảng giá</label>
                                <div class="input-group">
                                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}" placeholder="Giá tối thiểu">
                                    <span class="input-group-text bg-light">đến</span>
                                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="Giá tối đa">
                                </div>
                            </div>
                            <div class="col-md-2 ms-auto align-self-end">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa fa-filter me-1"></i> Lọc
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Hiển thị danh sách sản phẩm -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100 border-0 shadow-sm rounded-3 overflow-hidden transition-hover">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        @if($product->discount > 0)
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">-{{ (int) $product->discount }}%</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <p class="small text-danger mb-1">{{ $product->brand->name ?? 'Thương hiệu' }}</p>
                        <h5 class="card-title fw-bold text-truncate mb-1">{{ $product->name }}</h5>
                        <div class="mb-2">
                            <i class="text-warning fa fa-star"></i>
                            <i class="text-warning fa fa-star"></i>
                            <i class="text-warning fa fa-star"></i>
                            <i class="text-warning fa fa-star"></i>
                            <i class="text-muted fa fa-star"></i>
                            <span class="small text-muted ms-1">(135)</span>
                        </div>
                        <div class="d-flex align-items-center mt-auto">
                            <h5 class="text-success fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} ₫</h5>
                            @if($product->discount > 0)
                                <p class="ms-2 mb-0 text-decoration-line-through text-muted small">
                                    {{ number_format($product->price * (100 + $product->discount) / 100, 0, ',', '.') }} ₫
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-danger">
                                <i class="fa fa-eye me-1"></i> Xem chi tiết
                            </a>
  
                        </div>
                    </div>
                    
                </div>
            </div>
        @empty
            <div class="col-12 py-5">
                <div class="text-center p-4 rounded-3 bg-light">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Empty results" style="width: 120px; opacity: 0.5" class="mb-3">
                    <h4 class="text-muted">Không tìm thấy sản phẩm</h4>
                    <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc</p>
                    <a href="{{ route('user.products') }}" class="btn btn-outline-danger mt-2">
                        <i class="fa fa-refresh me-1"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        <nav>
            <ul class="pagination">
                {{-- Nút Previous --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">«</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">«</a>
                    </li>
                @endif
    
                {{-- Các số trang --}}
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
    
                {{-- Nút Next --}}
                @if ($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">»</a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link">»</span></li>
                @endif
            </ul>
        </nav>
    </div>
</div>

<style>
    .product-img {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-img {
        transform: scale(1.05);
    }
    
    .product-card {
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .transition-hover {
        transition: all 0.3s ease;
    }
</style>

<!-- Để hiển thị icons, thêm Font Awesome vào layout master của bạn -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
@endsection