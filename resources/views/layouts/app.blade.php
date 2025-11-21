<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Flower Shop')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{ --brand:#ff7a00; --brand-2:#ff9a3c; --text:#262626; }
    body{ color:var(--text); background:#f8f9fb; }
    .navbar .navbar-brand, .navbar .nav-link{ color:#fff!important; }
    .navbar .form-control::placeholder{ color:#fff; opacity:.85; }
    /* --- Navbar tweaks --- */
    .navbar { background: var(--brand); }
    .navbar .navbar-brand { color:#fff!important; font-weight:800; letter-spacing:.3px; font-size:1.6rem; } 
    @media (min-width:992px){
      .navbar .search-wrap{ max-width: 520px; }   
    }
    .navbar .nav-actions{ gap:18px; }   
    .navbar .nav-link{ color:#fff!important; padding-left:.25rem; padding-right:.25rem; }
    .navbar .nav-link .bi{ margin-right:6px; font-size:1rem; }

    .navbar .search-input{
      background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.35); color:#fff;
    }
    .navbar .search-input:focus{ background:rgba(255,255,255,.25); color:#fff; box-shadow:none; }

    /* Cart badge */
    .cart-icon{ position:relative; display:inline-block; }
    .cart-badge{
      position:absolute; top:-6px; right:-10px;
      background:#fff; color:#ff7a00; border:1px solid rgba(0,0,0,.05);
      font-size:.72rem; line-height:1; padding:.15rem .32rem;
    }
    footer{ background:var(--brand); color:#fff; }
    footer a{ color:#fff; opacity:.95; }
    footer a:hover{ color:#fff; opacity:1; text-decoration:underline; }
    footer .border-secondary{ border-color:rgba(255,255,255,.35)!important; }
    .btn-brand{ background:var(--brand); border:0; color:#fff; }
    .btn-brand:hover{ background:var(--brand-2); color:#fff; }
    .hero{ background:linear-gradient(180deg,#fff3e6,#fff); border:1px solid #ffe0c2; border-radius:16px; }
    .card-product .thumb{ width:100%; aspect-ratio:1/1; object-fit:cover; background:#e9ecef; border-top-left-radius:.5rem; border-top-right-radius:.5rem; }
    .badge-sale{ background:#ff7a00; }
    .price{ font-weight:700; } .old-price{ color:#6c757d; text-decoration:line-through; margin-left:.25rem; }
    .pagination svg{ width:16px; height:16px; }
    .btn-outline-secondary .bi-heart{ transition: color .15s; }
    .btn-outline-secondary:hover .bi-heart{ color: var(--brand); }

  </style>
  @stack('head')
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      🌼 FlowerShop
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="mainNav" class="collapse navbar-collapse">
      {{-- Ô tìm kiếm – thu gọn trên desktop --}}
      <div class="search-wrap w-100 w-lg-auto ms-lg-3 my-2 my-lg-0">
        <form action="{{ route('products.index') }}" method="get" role="search">
          <div class="input-group">
            <span class="input-group-text" style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35); color:#fff">
              <i class="bi bi-search"></i>
            </span>
            <input class="form-control search-input" name="q" placeholder="Tìm hoa..." aria-label="Tìm hoa">
          </div>
        </form>
      </div>

      @php
        $cartCount = collect(session('cart', []))->sum('qty');
      @endphp
      <ul class="navbar-nav ms-lg-3 nav-actions align-items-center flex-row">
        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-flower1"></i> Sản phẩm</a></li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('cart.index') }}">
            <span class="cart-icon">
              <i class="bi bi-bag"></i>
              @if($cartCount>0)
                <span class="cart-badge rounded-pill">{{ $cartCount }}</span>
              @endif
            </span>
            Giỏ hàng
          </a>
        </li>
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i>
              <span class="ms-2">Tài khoản</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
              <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> Hồ sơ</a></li>
              <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-clock-history me-2"></i> Lịch sử đơn hàng</a></li>
              <li><a class="dropdown-item" href="{{ route('vouchers.index') }}"><i class="bi bi-ticket-perforated me-2"></i> Mã giảm giá</a></li>
              <li><hr class="dropdown-divider"></li>
              @if(auth()->user() && auth()->user()->is_admin)
                <li><a class="dropdown-item" href="{{ url('/admin/products') }}"><i class="bi bi-tools me-2"></i> Quản trị</a></li>
              @endif
              <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i> Trang chủ</a></li>
              <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                  @csrf
                  <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Đăng ký</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

@if(trim($__env->yieldContent('breadcrumb')))
  <div class="border-bottom bg-white">
    <div class="container py-2">
      @yield('breadcrumb')
    </div>
  </div>
@endif

<main class="container my-4">
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @yield('content')
</main>

<footer class="pt-5 pb-4" style="background:#1f1f1f; color:#eee;">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h5 class="mb-3">🌼 FlowerShop</h5>
        <p class="mb-2">Hoa tươi giao trong ngày tại TP.HCM.</p>
        <p class="mb-1"><i class="bi bi-geo-alt"></i> 123 Đường Hoa, Q.1, TP.HCM</p>
        <p class="mb-1"><i class="bi bi-telephone"></i> 0900 090 100</p>
        <p class="mb-0"><i class="bi bi-envelope"></i> hello@flowershop.vn</p>
      </div>

      <div class="col-md-2">
        <h6 class="mb-3">Danh mục</h6>
        <ul class="list-unstyled">
          <li><a class="text-decoration-none text-light" href="{{ route('products.index',['category'=>\App\Models\Category::value('id')]) }}">Hoa Sinh Nhật</a></li>
          <li><a class="text-decoration-none text-light" href="{{ route('products.index') }}">Hoa Khai Trương</a></li>
          <li><a class="text-decoration-none text-light" href="{{ route('products.index') }}">Hoa Tình Yêu</a></li>
          <li><a class="text-decoration-none text-light" href="{{ route('products.index') }}">Giỏ Hoa</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h6 class="mb-3">Chính sách</h6>
        <ul class="list-unstyled">
          <li><a class="text-decoration-none text-light" href="{{ route('support.faq') }}">Trợ giúp & FAQ</a></li>
          <li><a class="text-decoration-none text-light" href="{{ route('support.contact') }}">Liên hệ chúng tôi</a></li>
          @auth
          <li><a class="text-decoration-none text-light" href="{{ route('support.tickets') }}">Yêu cầu của tôi</a></li>
          @endauth
          <li><a class="text-decoration-none text-light" href="#">Giao hàng & Đổi trả</a></li>
          <li><a class="text-decoration-none text-light" href="#">Bảo mật thông tin</a></li>
          <li><a class="text-decoration-none text-light" href="#">Điều khoản sử dụng</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h6 class="mb-3">Nhận ưu đãi</h6>
        <form class="d-flex gap-2">
          <input type="email" class="form-control" placeholder="Email của bạn">
          <button class="btn btn-brand">Đăng ký</button>
        </form>
        <div class="mt-3 d-flex gap-3 fs-4">
          <a class="text-light" href="#"><i class="bi bi-facebook"></i></a>
          <a class="text-light" href="#"><i class="bi bi-instagram"></i></a>
          <a class="text-light" href="#"><i class="bi bi-tiktok"></i></a>
          <a class="text-light" href="#"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>

    <hr class="border-secondary my-4">
    <div class="d-flex justify-content-between">
      <div>© {{ date('Y') }} FlowerShop — Made with ❤️</div>
      <div>
        <a href="{{ route('support.faq') }}" class="text-decoration-none" style="color:#ff9a3c">Trợ giúp</a> &nbsp;|&nbsp;
        <a href="{{ route('support.contact') }}" class="text-decoration-none" style="color:#ff9a3c">Hỗ trợ</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
  // Tự ẩn alert sau 2.5s
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
      el.classList.add('fade');
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(()=> el.remove(), 400);
    });
  }, 2500);
</script>
</body>
</html>
