<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
<style>
/* Sidebar toggle button */
.sidebar-toggle-btn {
    position: fixed;
    top: 70px;
    left: 250px; /* Ban đầu ở bên phải sidebar khi mở rộng */
    width: 40px;
    height: 40px;
    background-color: #343a40;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.sidebar-toggle-btn:hover {
    background-color: #495057;
}

/* Sidebar collapse styles */
.sidebar {
    transition: all 0.3s ease;
    width: 250px;
    min-height: 100vh;
    overflow: hidden;
}

.sidebar-collapsed .sidebar {
    width: 60px; /* Thu gọn thành mini sidebar */
}

.sidebar a {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: padding 0.3s ease;
}

.sidebar a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Khi thu gọn, chỉ hiện icons */
.sidebar-collapsed .sidebar a span {
    opacity: 0;
    display: none;
}

.sidebar-collapsed .sidebar a {
    padding: 10px;
    text-align: center;
}

.sidebar-collapsed .sidebar a i {
    margin-right: 0;
}

.sidebar-collapsed .sidebar h5 {
    opacity: 0;
    height: 0;
    margin: 0;
    padding: 0;
}

/* Điều chỉnh nội dung chính khi thu gọn sidebar */
.main-content {
    transition: margin-left 0.3s ease;
    margin-left: 250px;
}

.sidebar-collapsed .main-content {
    margin-left: 60px; /* Khoảng cách từ mini sidebar */
}

/* Điều chỉnh vị trí nút toggle khi sidebar thu gọn */
.sidebar-collapsed .sidebar-toggle-btn {
    left: 65px; /* Đặt sát với mini sidebar */
}
/* Đảm bảo rằng thông báo sẽ được hiển thị ở góc phải trên cùng */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999; /* Đảm bảo thông báo sẽ nằm trên tất cả các phần tử khác */
    max-width: 300px; /* Giới hạn chiều rộng của thông báo */
    margin-bottom: 10px; /* Khoảng cách giữa các thông báo */
}

.notification .alert {
    margin-bottom: 0;
}


</style>
    @yield('additional_css')
</head>
<body>
    <!-- Navbar trên cùng -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-tshirt"></i> FashionHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/products-for-user') }}">
                            <i class="fas fa-store"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/cart') }}" class="nav-link"><i class="fas fa-shopping-cart"></i> Giỏ hàng</a>
                    </li>
                    @if(Auth::check())  
                        <!-- Hiển thị tên user và nút đăng xuất -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->username }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item user-action" href="{{ url('/profile') }}"><i class="fas fa-id-card"></i> Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item user-action" href="{{ url('/orders/history') }}"><i class="fas fa-list"></i> Đơn hàng</a></li>
                                <li><a class="dropdown-item user-action" href="{{ url('/change-password') }}"><i class="fas fa-key"></i> Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item user-action"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
                                    </form>
                                </li>                                
                            </ul>
                        </li>
                    @else
                        <!-- Hiển thị nút đăng nhập và đăng ký nếu chưa đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bố cục chính: Sidebar + Nội dung -->
    <div class="content-wrapper">
        <!-- Include sidebar -->
        @include('partials.sidebar')

        <!-- Nội dung chính -->
        <div class="main-content page-transition">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show notification" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show notification" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
            
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <p class="mb-0">&copy; 2025 FashionHub - Tất cả các quyền được bảo lưu</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Thiết kế bởi Mạnh Mạnh Mạnh Mạnh</p>
                </div>
            </div>
        </div>
    </div>
    @yield('scripts')


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const sidebarToggle = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        
        // Kiểm tra trạng thái trên localStorage khi tải trang
        // Mặc định khi F5 sidebar sẽ ở trạng thái thu gọn
        if (localStorage.getItem('sidebarCollapsed') === null) {
            // Nếu là lần đầu truy cập, mặc định là thu gọn
            localStorage.setItem('sidebarCollapsed', 'true');
            body.classList.add('sidebar-collapsed');
            toggleIcon.classList.remove('fa-angle-left');
            toggleIcon.classList.add('fa-angle-right');
        } else if (localStorage.getItem('sidebarCollapsed') === 'true') {
            // Nếu trước đó đã thu gọn, giữ nguyên trạng thái
            body.classList.add('sidebar-collapsed');
            toggleIcon.classList.remove('fa-angle-left');
            toggleIcon.classList.add('fa-angle-right');
        }
        
        // Chuyển đổi sidebar khi nhấn nút
        sidebarToggle.addEventListener('click', function() {
            body.classList.toggle('sidebar-collapsed');
            
            // Chuyển đổi biểu tượng mũi tên
            if (body.classList.contains('sidebar-collapsed')) {
                toggleIcon.classList.remove('fa-angle-left');
                toggleIcon.classList.add('fa-angle-right');
            } else {
                toggleIcon.classList.remove('fa-angle-right');
                toggleIcon.classList.add('fa-angle-left');
            }
            
            // Lưu trạng thái vào localStorage
            localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
        });
    });
    </script>
</body>
</html>
