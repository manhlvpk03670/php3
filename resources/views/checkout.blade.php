@extends('layout.master')
@section('content')
<div class="container py-5">
    <div class="checkout-wrapper" style="display: flex; flex-wrap: wrap; gap: 2rem;">

        {{-- Cột trái: Danh sách sản phẩm --}}
        <div class="cart-items" style="flex: 1 1 60%;">
            <h2>Thanh toán đơn hàng</h2>
            <p class="text-muted mb-4">Xác nhận đơn hàng và chọn phương thức thanh toán</p>

            @foreach ($carts as $cart)
                <div class="cart-item d-flex align-items-center mb-3 p-3 border rounded" style="gap: 1rem;">
                    <div class="cart-item-image" style="width: 100px;">
                        <img src="{{ Storage::url($cart->productVariant->image ?? $cart->productVariant->product->image) }}"
                             alt="{{ $cart->productVariant->product->name }}" class="img-fluid rounded">
                    </div>
                    <div class="cart-item-details flex-grow-1">
                        <h5 class="mb-1">{{ $cart->productVariant->product->name }}</h5>
                        <p class="text-muted mb-0">{{ number_format($cart->price, 0, ',', '.') }} VND</p>
                    </div>
                    <div class="cart-item-controls text-end">
                        <input type="number" class="form-control form-control-sm mb-1" value="{{ $cart->quantity }}" disabled>
                        <strong class="text-danger">{{ number_format($cart->price * $cart->quantity, 0, ',', '.') }} VND</strong>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cột phải: Thông tin người dùng, địa chỉ, thanh toán --}}
        <div class="cart-summary" style="flex: 1 1 35%;">
            <div class="p-4 border rounded">
                <h4 class="mb-3">Tóm tắt đơn hàng</h4>
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-between mb-2">
                        <span>Tổng sản phẩm:</span>
                        <span>{{ count($carts) }}</span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($subtotal, 0, ',', '.') }} VND</span>
                    </li>
                    
                    @if(Session::has('coupon_code') && isset($discount) && $discount > 0)
                    <li class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span>-{{ number_format($discount, 0, ',', '.') }} VND</span>
                    </li>
                    @endif
                    
                    <li class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </li>
                    <li class="d-flex justify-content-between border-top pt-2 fw-bold text-danger">
                        <span>Tổng thanh toán:</span>
                        <span id="grand-total">
                            {{ number_format(isset($total) ? $total : $subtotal, 0, ',', '.') }} VND
                        </span>
                    </li>
                </ul>

                {{-- Phần mã giảm giá --}}
                <div class="coupon-section mb-4 border-top pt-3">
                    <h5>Mã giảm giá</h5>
                    @if(Session::has('coupon_code'))
                        <div class="applied-coupon mt-2 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-success">{{ Session::get('coupon_code') }}</span>
                                @if(isset($coupon))
                                    @if($coupon->discount_percent > 0)
                                        <span class="ms-2 text-success">-{{ $coupon->discount_percent }}%</span>
                                    @else
                                        <span class="ms-2 text-success">-{{ number_format($coupon->discount_amount, 0, ',', '.') }} VND</span>
                                    @endif
                                @endif
                            </div>
                            <a href="{{ route('checkout.remove-coupon') }}" class="text-danger">
                                <i class="fas fa-times"></i> Xóa
                            </a>
                        </div>
                    @else
                        <form action="{{ route('checkout.apply-coupon') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá">
                            <button type="submit" class="btn btn-outline-primary">Áp dụng</button>
                        </form>
                    @endif
                </div>

                <form action="{{ route('checkout.process') }}" method="POST" class="mt-4">
                    @csrf
                    <h5>Thông tin người dùng</h5>
                    <div class="mb-2">
                        <label for="username" class="form-label">Tên người dùng:</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->username }}" disabled>
                    </div>
                    <div class="mb-2">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                    </div>

                    <h5 class="mt-3">Thông tin giao hàng</h5>
                    <div class="mb-2">
                        <label class="form-label" for="recipient_name">Tên người nhận:</label>
                        <input type="text" class="form-control" name="recipient_name" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="recipient_phone">Số điện thoại:</label>
                        <input type="text" class="form-control" name="recipient_phone" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="recipient_address">Địa chỉ:</label>
                        <input type="text" class="form-control" name="recipient_address" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="province_code">Tỉnh:</label>
                        <select class="form-control" name="province_code" id="province_code" required>
                            @foreach($provinces as $province)
                                <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="district_code">Quận/Huyện:</label>
                        <select class="form-control" name="district_code" id="district_code" required>
                            <!-- Dữ liệu quận huyện sẽ được load động -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Phương thức thanh toán:</label>
                        <select class="form-control" name="payment_method" id="payment_method" required>
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="vnpay">Thanh toán qua VNPAY</option>
                            <option value="momo">Thanh toán qua MOMO</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Xác nhận thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Khi người dùng chọn tỉnh, sẽ load danh sách quận huyện tương ứng
    document.getElementById('province_code').addEventListener('change', function() {
        var provinceCode = this.value;
        
        // Tìm tỉnh đã chọn trong danh sách tỉnh
        var province = @json($provinces);
        var selectedProvince = province.find(function(p) {
            return p.code == provinceCode;
        });
        
        // Clear quận huyện cũ
        var districtSelect = document.getElementById('district_code');
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';  // Reset
    
        // Nếu tìm thấy tỉnh và có quận huyện thì load quận huyện vào select
        if (selectedProvince && selectedProvince.districts) {
            selectedProvince.districts.forEach(function(district) {
                var option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
        }
    });
    
    // Trigger sự kiện để load quận huyện mặc định khi trang được load (nếu có tỉnh mặc định)
    document.addEventListener('DOMContentLoaded', function() {
        var provinceCode = document.getElementById('province_code').value;
        if (provinceCode) {
            document.getElementById('province_code').dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection