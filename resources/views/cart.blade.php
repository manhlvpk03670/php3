@extends('layout.master')

@section('content')
    <div class="cart-container">
        <div class="cart-header">
            <h2>Giỏ hàng của bạn</h2>
            <p class="cart-subtitle">Các sản phẩm bạn đã chọn</p>
        </div>

        @if (count($carts) > 0)
            <div class="cart-content">
                <div class="cart-items">
                    @foreach ($carts as $cart)
                        <div class="cart-item" data-id="{{ $cart->id }}">
                            <div class="cart-item-image">
                                <img src="{{ Storage::url($cart->productVariant->image ?? $cart->productVariant->product->image) }}"
                                    alt="{{ $cart->productVariant->product->name }}">
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-name">{{ $cart->productVariant->product->name }}</h3>
                                <p class="cart-item-price">{{ number_format($cart->price, 0, ',', '.') }} VND</p>
                            </div>
                            <div class="cart-item-controls">
                                <div class="quantity-control">
                                    <button class="quantity-btn minus">-</button>
                                    <input type="number" class="quantity-input" value="{{ $cart->quantity }}"
                                        min="1">
                                    <button class="quantity-btn plus">+</button>
                                </div>
                                <p class="cart-item-total">{{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}
                                    VND</p>
                                <button class="remove-btn" title="Xóa sản phẩm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <h3>Tóm tắt đơn hàng</h3>
                    <div class="summary-item">
                        <span>Tổng sản phẩm:</span>
                        <span id="total-items">{{ count($carts) }}</span>
                    </div>
                    <div class="summary-item">
                        <span>Tạm tính:</span>
                        <span id="subtotal">0 VND</span>
                    </div>
                    <div class="summary-item">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-item coupon">
                        <input type="text" placeholder="Nhập mã giảm giá">
                        <button class="apply-btn">Áp dụng</button>
                    </div>
                    <div class="summary-total" style="font-size: 18px; color:#dc3545">
                        <span>Tổng thanh toán:</span>
                        <span id="grand-total">0 VND</span>
                    </div>
                
                    <!-- Thêm liên kết chuyển đến trang checkout -->
                    <a href="{{ route('checkout') }}" class="checkout-btn">Tiến hành thanh toán</a>
                </div>                
            </div>
        @else
            <div class="empty-cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <h3>Giỏ hàng của bạn đang trống</h3>
                <p>Khám phá cửa hàng và thêm sản phẩm vào giỏ hàng của bạn</p>
                <button class="continue-shopping">Bắt đầu mua sắm</button>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay">
            <div class="spinner"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateTotals();
            const loadingOverlay = document.getElementById('loading-overlay');

            // Hiển thị loading
            function showLoading() {
                loadingOverlay.classList.add('active');
            }

            // Ẩn loading
            function hideLoading() {
                loadingOverlay.classList.remove('active');
            }

            // Tăng số lượng
            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                    updateCart(this.closest('.cart-item'));
                });
            });

            // Giảm số lượng
            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.quantity-input');
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateCart(this.closest('.cart-item'));
                    }
                });
            });

            // Cập nhật khi thay đổi số lượng trực tiếp
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    if (parseInt(this.value) < 1) {
                        this.value = 1;
                    }
                    updateCart(this.closest('.cart-item'));
                });
            });

            // Xóa sản phẩm
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    const cartId = item.dataset.id;

                    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                        showLoading();
                        fetch(`{{ url('/cart/delete') }}/${cartId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                showNotification(data.message);
                                item.classList.add('removing');
                                setTimeout(() => {
                                    item.remove();
                                    updateTotals();

                                    // Kiểm tra nếu giỏ hàng trống
                                    if (document.querySelectorAll('.cart-item')
                                        .length === 0) {
                                        location
                                            .reload(); // Tải lại trang để hiển thị giỏ hàng trống
                                    }
                                }, 300);
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Lỗi:', error);
                                showNotification('Đã xảy ra lỗi khi xóa sản phẩm');
                            });
                    }
                });
            });

            // Cập nhật giỏ hàng khi thay đổi số lượng
            function updateCart(item) {
                const cartId = item.dataset.id;
                const quantityInput = item.querySelector('.quantity-input');
                const quantity = parseInt(quantityInput.value);
                const priceElement = item.querySelector('.cart-item-price');
                const priceText = priceElement.textContent;
                const price = parseFloat(priceText.replace(/\D/g, ''));

                // Cập nhật tổng tiền trước khi gửi request
                const totalElement = item.querySelector('.cart-item-total');
                const totalPrice = price * quantity;
                totalElement.textContent = totalPrice.toLocaleString() + ' VND';

                // Hiển thị loading
                showLoading();

                // Sử dụng form data thay vì JSON để phù hợp với Laravel
                const formData = new FormData();
                formData.append('quantity', quantity);
                formData.append('_token', '{{ csrf_token() }}');

                // Gửi request cập nhật
                fetch(`{{ url('/cart/update') }}/${cartId}`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Lỗi cập nhật giỏ hàng');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        showNotification('Giỏ hàng đã được cập nhật');
                        updateTotals();
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Lỗi cập nhật giỏ hàng:', error);
                        showNotification('Đạt giới hạn số lượng sản phẩm ');
                    });
            }

            // Tính tổng tiền
            function updateTotals() {
                let grandTotal = 0;
                let totalItems = 0;

                document.querySelectorAll('.cart-item').forEach(item => {
                    const quantityInput = item.querySelector('.quantity-input');
                    const totalPriceElement = item.querySelector('.cart-item-total');
                    const quantity = parseInt(quantityInput.value);
                    const totalPrice = parseFloat(totalPriceElement.textContent.replace(/\D/g, ''));

                    grandTotal += totalPrice;
                    totalItems += quantity;
                });

                document.getElementById('subtotal').textContent = grandTotal.toLocaleString() + ' VND';
                document.getElementById('grand-total').textContent = grandTotal.toLocaleString() + ' VND';
                document.getElementById('total-items').textContent = totalItems;
            }

            // Hiển thị thông báo
            function showNotification(message) {
                // Kiểm tra xem đã có thông báo nào hiển thị chưa
                let notification = document.querySelector('.notification');

                if (notification) {
                    // Nếu đã có, cập nhật nội dung
                    notification.textContent = message;
                    // Reset animation
                    notification.classList.remove('show');
                    void notification.offsetWidth; // Trigger reflow
                    notification.classList.add('show');
                } else {
                    // Nếu chưa có, tạo thông báo mới
                    notification = document.createElement('div');
                    notification.className = 'notification';
                    notification.textContent = message;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.classList.add('show');
                    }, 10);
                }

                // Tự động ẩn thông báo sau 3 giây
                clearTimeout(notification.hideTimeout);
                notification.hideTimeout = setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }

            // Nút tiếp tục mua sắm
            document.querySelectorAll('.continue-shopping').forEach(button => {
                button.addEventListener('click', function() {
                    window.location.href = '/products';
                });
            });

            // Nút thanh toán
            document.querySelector('.checkout-btn')?.addEventListener('click', function() {
                window.location.href = '/checkout';
            });
        });
    </script>

    <style>
        /* Reset và font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Container chính */
        .cart-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            position: relative;
            /* Cho loading overlay */
        }

        .cart-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .cart-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .cart-subtitle {
            color: #666;
            font-size: 16px;
        }

        /* Layout chính */
        .cart-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        @media (max-width: 900px) {
            .cart-content {
                grid-template-columns: 1fr;
            }
        }

        /* Các item sản phẩm */
        .cart-items {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr 200px;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: transform 0.3s, opacity 0.3s;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item.removing {
            transform: translateX(-10%);
            opacity: 0;
        }

        .cart-item-image {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-image img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .cart-item-image img:hover {
            transform: scale(1.05);
        }

        .cart-item-details {
            padding: 0 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cart-item-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .cart-item-price {
            font-size: 16px;
            color: #666;
        }

        .cart-item-controls {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-end;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid #e1e1e1;
            border-radius: 50px;
            overflow: hidden;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            background: #f8f9fa;
            border: none;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .quantity-btn:hover {
            background: #e9ecef;
        }

        .quantity-input {
            width: 40px;
            height: 36px;
            border: none;
            text-align: center;
            font-size: 16px;
            outline: none;
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .cart-item-total {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .remove-btn:hover {
            transform: scale(1.15);
        }

        /* Tóm tắt đơn hàng */
        .cart-summary {
            background: white;
            border-radius: 12px;
            padding: 25px;
            height: fit-content;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .cart-summary h3 {
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            color: #666;
        }

        .summary-item.coupon {
            display: flex;
            flex-direction: column;
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px dashed #eee;
            border-bottom: 1px dashed #eee;
        }

        .summary-item.coupon input {
            padding: 12px;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .apply-btn {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .apply-btn:hover {
            background: #e9ecef;
        }

        .summary-total {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            background: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        .continue-shopping {
            width: 100%;
            padding: 15px;
            background: transparent;
            color: #4361ee;
            border: 1px solid #4361ee;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .continue-shopping:hover {
            background: rgba(67, 97, 238, 0.05);
        }

        /* Giỏ hàng trống */
        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .empty-cart svg {
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .empty-cart p {
            color: #666;
            margin-bottom: 30px;
            max-width: 400px;
        }

        .empty-cart .continue-shopping {
            max-width: 250px;
        }

        /* Thông báo */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4361ee;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #4361ee;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cart-content {
                grid-template-columns: 1fr;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                grid-template-rows: auto auto;
            }

            .cart-item-image {
                grid-row: span 2;
            }

            .cart-item-controls {
                grid-column: 2;
                flex-direction: row;
                margin-top: 15px;
                align-items: center;
                justify-content: space-between;
            }

            .cart-item-total {
                margin: 0 15px;
            }
        }

        @media (max-width: 480px) {
            .cart-container {
                padding: 0 10px;
                margin: 30px auto;
            }

            .cart-header h2 {
                font-size: 24px;
            }

            .cart-item {
                padding: 15px;
            }

            .cart-item-name {
                font-size: 16px;
            }

            .remove-btn {
                transform: scale(0.8);
            }

            .summary-total {
                font-size: 18px;
            }

            .checkout-btn,
            .continue-shopping {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
@endsection
