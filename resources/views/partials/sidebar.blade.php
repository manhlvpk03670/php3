@if(Auth::check() && Auth::user()->role === 'admin')
    <div class="sidebar">
        <h5><i class="fas fa-cogs"></i> <span>Quản lý Admin</span></h5>
        <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> <span>Tổng quan</span>
        </a>
        <a href="{{ url('/categories') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
            <i class="fas fa-th-list"></i> <span>Danh mục</span>
        </a>
        <a href="{{ url('/brands') }}" class="{{ request()->is('brands*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> <span>Thương hiệu</span>
        </a>
        <a href="{{ url('/variants') }}" class="{{ request()->is('variants*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>Biến thể</span>
        </a>
        <a href="{{ url('/sizes') }}" class="{{ request()->is('sizes*') ? 'active' : '' }}">
            <i class="fas fa-ruler"></i> <span>Sizes</span>
        </a>
        <div class="sidebar-toggle-btn" id="sidebarToggle">
            <i class="fas fa-angle-left" id="toggleIcon"></i>
        </div>
        <a href="{{ url('/colors') }}" class="{{ request()->is('colors*') ? 'active' : '' }}">
            <i class="fas fa-palette"></i> <span>Colors</span>
        </a>
        <a href="{{ url('/products') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>Sản phẩm</span>
        </a>
        <a href="{{ url('/admin/manage-orders') }}" class="{{ request()->is('orders*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i> <span>Đơn hàng</span>
        </a>
        <a href="{{ url('/coupons') }}" class="{{ request()->is('orders*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> <span>Giảm giá</span>
        </a>
        <a href="{{ url('/users') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>Người dùng</span>
        </a>

        <a href="{{ url('/settings') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> <span>Cài đặt</span>
        </a>

    </div>

@endif