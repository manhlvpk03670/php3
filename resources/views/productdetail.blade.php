@extends('layout.master')

@section('content')
    <style>
        .custom-alert-red {
            color: red;
            border: 1px solid red;
            background-color: transparent;
            margin-bottom: 15px
        }
    </style>
    <div class="container py-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-danger">Trang
                        chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Image Gallery Section -->
            <div class="col-lg-6 mb-4">
                <!-- Main Product Image -->
                <div class="product-image-container bg-light rounded p-3 d-flex justify-content-center align-items-center"
                    style="height: 450px;">
                    <img id="product-image" src="{{ asset('storage/' . $product->image) }}"
                        class="img-fluid rounded product-image" alt="{{ $product->name }}"
                        style="max-height: 400px; object-fit: contain;">
                </div>

                <!-- Image Thumbnails -->
                <div class="product-thumbnails mt-3 d-flex overflow-auto">
                    <img src="{{ asset('storage/' . $product->image) }}" class="thumbnail-img me-2 border rounded p-1"
                        style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                        onclick="changeMainImage(this.src)">
                    @foreach ($product->variants as $variant)
                        @if ($variant->image)
                            <img src="{{ asset('storage/' . $variant->image) }}"
                                class="thumbnail-img me-2 border rounded p-1"
                                style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                onclick="changeMainImage(this.src)">
                        @endif
                    @endforeach
                </div>
                <!-- Warranty & Returns Info -->
                <div class="mt-4 p-3 border rounded bg-light">
                    <h6 class="fw-bold text-danger mb-2">Chính sách & Dịch vụ</h6>
                    <div class="d-flex mb-2">
                        <i class="bi bi-shield-check me-2 text-danger"></i>
                        <span>Bảo hành chính hãng 12 tháng</span>
                    </div>
                    <div class="d-flex mb-2">
                        <i class="bi bi-arrow-repeat me-2 text-danger"></i>
                        <span>Đổi trả trong vòng 7 ngày</span>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-truck me-2 text-danger"></i>
                        <span>Giao hàng toàn quốc</span>
                    </div>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="col-lg-6">
                <!-- Product Title and Rating -->
                <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <span class="stars">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </span>
                    </div>
                    <span class="text-muted">({{ rand(10, 100) }} đánh giá)</span>
                </div>

                <!-- Price -->
                <h3 class="text-danger fw-bold mb-4"><span
                        id="price">{{ number_format($product->price, 0, ',', '.') }}</span> VNĐ</h3>

                <!-- Product Metadata -->
                <div class="product-meta mb-4 p-3 border rounded bg-light">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-tag me-2 text-danger"></i>
                                <span><strong>Thương hiệu:</strong> {{ $product->brand->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-folder me-2 text-danger"></i>
                                <span><strong>Danh mục:</strong> {{ $product->category->name }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck me-2 text-danger"></i>
                                <span><strong>Giao hàng:</strong> Miễn phí (nội thành)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="product-description mb-4">
                    <h5 class="fw-bold">Mô tả sản phẩm</h5>
                    <p>{{ $product->description }}</p>
                </div>

                <hr class="my-4">
                <!-- Availability Message -->
                <div id="availability-message" class="rounded-3 w-50 py-2 text-center shadow-sm custom-alert-red">
                    <i class="bi bi-info-circle me-2"></i>Vui lòng chọn size và màu sắc
                </div>

                <!-- Product Selection Form -->
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selected_variant_id">

                    <!-- Size Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Chọn Size</label>
                        <div class="size-options d-flex flex-wrap">
                            @php
                                $sizes = $product->variants->pluck('size')->unique('id');
                            @endphp
                            @foreach ($sizes as $size)
                                <button type="button" class="btn btn-outline-dark size-btn me-2 mb-2"
                                    data-size-id="{{ $size->id }}" style="border-radius: 30px; min-width: 60px;">
                                    {{ $size->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Color Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Chọn Màu</label>
                        <div class="d-flex flex-column">
                            <div class="color-options d-flex flex-wrap mb-2">
                                @php
                                    $colors = $product->variants->pluck('color')->unique('id');
                                @endphp
                                @foreach ($colors as $color)
                                    <button type="button" class="btn color-btn me-2 mb-2 position-relative"
                                        data-color-id="{{ $color->id }}" data-color-name="{{ $color->name }}"
                                        style="background-color: {{ $color->code }}; width: 40px; height: 40px; border: 2px solid #e9e9e9; border-radius: 50%;">
                                        <span class="visually-hidden">{{ $color->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <div id="selected-color-name" class="mt-2 fw-bold">Chưa chọn màu</div>
                        </div>
                    </div>

                    <!-- Quantity Selection -->
                    <div class="mb-4">
                        <label for="quantity" class="form-label fw-bold">Số lượng</label>
                        <div class="quantity-selector d-flex">
                            <button type="button" class="btn btn-outline-dark quantity-btn minus"
                                onclick="decrementQuantity()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center mx-2"
                                min="1" value="1" style="max-width: 80px;">
                            <button type="button" class="btn btn-outline-dark quantity-btn plus"
                                onclick="incrementQuantity()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>



                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" id="add-to-cart-btn" class="btn btn-danger px-4 py-2 fw-bold flex-grow-1"
                            disabled>
                            <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>
    <!-- Sản phẩm liên quan -->
    <div class="related-products mt-5">
        <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
        <div class="row g-3">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col-md-3 col-sm-6">
                    <div class="product-card border rounded shadow-sm">
                        <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}"
                            class="img-fluid rounded mb-2" style="object-fit: cover; height: 180px;">
                        <h5 class="fw-semibold mb-2" style="font-size: 1rem;">{{ $relatedProduct->name }}</h5>
                        <p class="text-danger mb-2" style="font-size: 1.1rem;">
                            {{ number_format($relatedProduct->price, 0, ',', '.') }} VND</p>
                        <a href="{{ route('products.show', $relatedProduct->id) }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-eye me-2"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Link icon Bootstrap nếu chưa có -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FORM đánh giá -->
    @if (Auth::check())
        <form action="{{ route('reviews.store') }}" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-2">
                <label for="rating" class="form-label">Đánh giá:</label>
                <select name="rating" id="rating" required class="form-select">
                    <option value="">Chọn số sao</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} sao</option>
                    @endfor
                </select>
            </div>

            <div class="mb-2">
                <label for="comment" class="form-label">Bình luận:</label>
                <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Nhập bình luận..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill me-1"></i> Gửi đánh giá</button>
        </form>
    @else
        <div class="alert alert-warning">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đánh giá sản phẩm.
        </div>
    @endif

    <!-- Danh sách đánh giá -->
    <h4 class="mt-4 mb-3">Tất cả đánh giá</h4>

    @if ($reviews->count() > 0)
        @foreach ($reviews as $review)
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-4 text-secondary me-2"></i>
                            <div>
                                <strong>{{ $review->user->username ?? 'Người dùng ẩn danh' }}</strong>
                                <div class="text-warning small">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </div>

                    <!-- Nội dung bình luận -->
                    <p class="mb-2">{{ $review->comment }}</p>

                    @if (Auth::check() && Auth::id() === $review->user_id)
                        <div class="d-flex gap-2 mt-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleEditForm({{ $review->id }})">
                                <i class="bi bi-pencil-square"></i> Sửa
                            </button>

                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash3"></i> Xóa
                                </button>
                            </form>
                        </div>

                        <!-- Form sửa (ẩn mặc định) -->
                        <form id="edit-form-{{ $review->id }}" class="mt-3" method="POST"
                            action="{{ route('reviews.update', $review->id) }}" style="display: none;">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                <label class="form-label">Cập nhật đánh giá:</label>
                                <select name="rating" class="form-select" required>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}"
                                            {{ $review->rating == $i ? 'selected' : '' }}>
                                            {{ $i }} sao
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-2">
                                <textarea name="comment" class="form-control" rows="3">{{ $review->comment }}</textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-sm">Cập nhật</button>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="toggleEditForm({{ $review->id }})">Hủy</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">Chưa có đánh giá nào cho sản phẩm này.</div>
    @endif

    <!-- JavaScript toggle form sửa -->
    <script>
        function toggleEditForm(id) {
            const form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>


    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
            .product-image {
                transition: transform 0.3s ease;
            }

            /* Size button styles */
            .size-btn {
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }

            .size-btn.active {
                background-color: #dc3545 !important;
                border-color: #dc3545 !important;
                color: white !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            /* Color button styles */
            .color-btn {
                transition: all 0.2s ease;
                position: relative;
                overflow: visible;
            }

            .color-btn.active {
                transform: scale(1.1);
                box-shadow: 0 0 0 3px #fff, 0 0 0 5px #dc3545;
                z-index: 1;
            }

            .color-btn.active::after {
                content: '';
                position: absolute;
                bottom: -15px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-bottom: 5px solid #dc3545;
            }

            .thumbnail-img:hover {
                border-color: #dc3545 !important;
            }

            .quantity-btn {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Animation for image change */
            @keyframes fadeIn {
                from {
                    opacity: 0.5;
                }

                to {
                    opacity: 1;
                }
            }

            .image-fade {
                animation: fadeIn 0.5s;
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantsData = [];

            @foreach ($product->variants as $variant)
                variantsData.push({
                    id: {{ $variant->id }},
                    size_id: {{ $variant->size->id }},
                    color_id: {{ $variant->color->id }},
                    color_name: "{{ $variant->color->name }}",
                    color_code: "{{ $variant->color->code }}", // Giả sử bạn đã thêm trường code
                    price: {{ $variant->price }},
                    quantity: {{ $variant->quantity }},
                    image: "{{ $variant->image ? asset('storage/' . $variant->image) : asset('storage/' . $product->image) }}"
                });
            @endforeach

            // Định nghĩa mapping màu cơ bản nếu không có mã màu
            const colorMapping = {
                'Red': '#FF0000',
                'Green': '#00FF00',
                'Blue': '#0000FF',
                'Yellow': '#FFFF00',
                'Black': '#000000',
                'White': '#FFFFFF',
                // Thêm các màu khác nếu cần
            };

            let selectedSizeId = null;
            let selectedColorId = null;

            const sizeBtns = document.querySelectorAll('.size-btn');
            const colorBtns = document.querySelectorAll('.color-btn');
            const priceElement = document.getElementById('price');
            const productImage = document.getElementById('product-image');
            const variantIdInput = document.getElementById('selected_variant_id');
            const addToCartBtn = document.getElementById('add-to-cart-btn');
            const availabilityMessage = document.getElementById('availability-message');
            const selectedColorName = document.getElementById('selected-color-name');

            // Cập nhật màu cho các nút màu dựa trên tên màu hoặc mã màu
            colorBtns.forEach(btn => {
                const colorName = btn.getAttribute('data-color-name');
                // Cố gắng lấy màu từ mapping nếu không có sẵn style background-color
                if (!btn.style.backgroundColor || btn.style.backgroundColor === '') {
                    if (colorMapping[colorName]) {
                        btn.style.backgroundColor = colorMapping[colorName];
                    }
                }
            });

            // Initialize size buttons
            sizeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Reset all buttons
                    sizeBtns.forEach(b => {
                        b.classList.remove('active');
                        b.classList.add('btn-outline-dark');
                    });

                    // Activate selected button
                    this.classList.add('active');
                    this.classList.remove('btn-outline-dark');

                    selectedSizeId = parseInt(this.getAttribute('data-size-id'));
                    updateVariant();
                });
            });

            // Initialize color buttons
            colorBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Reset all buttons
                    colorBtns.forEach(b => b.classList.remove('active'));

                    // Activate selected button
                    this.classList.add('active');

                    selectedColorId = parseInt(this.getAttribute('data-color-id'));

                    // Show selected color name
                    const colorName = this.getAttribute('data-color-name');
                    const colorCode = this.style.backgroundColor;

                    // Cập nhật hiển thị với mẫu màu
                    selectedColorName.innerHTML = `
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: ${colorCode}; 
                                   border-radius: 50%; vertical-align: middle; margin-right: 8px; border: 1px solid #ddd;"></span>
                        ${colorName || 'Unknown'}
                    `;

                    updateVariant();
                });
            });

            function updateVariant() {
                if (selectedSizeId && selectedColorId) {
                    const matchedVariant = variantsData.find(v =>
                        v.size_id === selectedSizeId && v.color_id === selectedColorId
                    );

                    if (matchedVariant) {
                        // Update price
                        priceElement.textContent = new Intl.NumberFormat('vi-VN').format(matchedVariant.price);

                        // Update image if available
                        if (matchedVariant.image) {
                            changeMainImage(matchedVariant.image);
                        }

                        // Set selected variant ID for form submission
                        variantIdInput.value = matchedVariant.id;

                        // Check availability and update UI
                        if (matchedVariant.quantity <= 0) {
                            addToCartBtn.disabled = true;
                            availabilityMessage.className = 'alert alert-danger rounded-3';
                            availabilityMessage.innerHTML =
                                '<i class="bi bi-exclamation-triangle me-2"></i>Sản phẩm tạm hết hàng';
                        } else {
                            addToCartBtn.disabled = false;
                            availabilityMessage.className = 'alert alert-success rounded-3';
                            availabilityMessage.innerHTML = '<i class="bi bi-check-circle me-2"></i>Còn ' +
                                matchedVariant.quantity + ' sản phẩm';

                            // Cập nhật giới hạn số lượng trong ô nhập
                            const quantityInput = document.getElementById('quantity');
                            quantityInput.setAttribute('max', matchedVariant.quantity);
                        }
                    } else {
                        // No matching variant found
                        addToCartBtn.disabled = true;
                        availabilityMessage.className = 'alert alert-warning rounded-3';
                        availabilityMessage.innerHTML =
                            '<i class="bi bi-exclamation-circle me-2"></i>Không có sản phẩm phù hợp với lựa chọn của bạn';
                    }
                } else {
                    // Not all options selected yet
                    addToCartBtn.disabled = true;
                    availabilityMessage.className = 'alert alert-info rounded-3';
                    availabilityMessage.innerHTML =
                        '<i class="bi bi-info-circle me-2"></i>Vui lòng chọn size và màu sắc';
                }
            }
        });

        function changeMainImage(src) {
            const productImage = document.getElementById('product-image');
            productImage.classList.remove('image-fade');
            setTimeout(() => {
                productImage.src = src;
                productImage.classList.add('image-fade');
            }, 50);
        }

        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const maxQuantity = parseInt(quantityInput.getAttribute('max'));
            if (parseInt(quantityInput.value) < maxQuantity) {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            }
        }

        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }
    </script>

@endsection
<style>
    .custom-alert-red {
        color: red;
        border: 1px solid red;
        background-color: transparent;
        margin-bottom: 15px
    }

    /* Style cho phần sản phẩm liên quan */
    .related-products h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .related-products .product-card {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .related-products .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .related-products .product-card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
    }

    .related-products .product-card h5 {
        font-size: 1.1rem;
        font-weight: bold;
        margin-top: 10px;
    }

    .related-products .product-card p {
        color: #e74c3c;
        font-size: 1.2rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .related-products .product-card .btn {
        background-color: #e74c3c;
        color: #fff;
        border-radius: 25px;
        text-transform: uppercase;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .related-products .product-card .btn:hover {
        background-color: #c0392b;
    }
</style>
