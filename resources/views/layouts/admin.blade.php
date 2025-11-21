<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin Panel') - Flower Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root{ --brand:#ff7a00; --sidebar-bg:#2c3e50; --sidebar-text:#ecf0f1; }
    body{ background:#f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .sidebar {
      min-height: 100vh;
      background: var(--sidebar-bg);
      color: var(--sidebar-text);
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      padding: 0;
      z-index: 1000;
    }
    .sidebar .brand {
      padding: 1.5rem 1rem;
      background: #1a252f;
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--brand);
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar .nav {
      flex-direction: column;
      padding: 1rem 0;
    }
    .sidebar .nav-link {
      color: var(--sidebar-text);
      padding: 0.75rem 1.5rem;
      border-left: 3px solid transparent;
      transition: all 0.3s;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: rgba(255,255,255,0.1);
      border-left-color: var(--brand);
      color: #fff;
    }
    .sidebar .nav-link i {
      width: 20px;
      margin-right: 10px;
    }
    .main-content {
      margin-left: 250px;
      padding: 0;
    }
    .topbar {
      background: #fff;
      padding: 1rem 2rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .content-wrapper {
      padding: 2rem;
    }
    .card {
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
    }
    .card-header {
      background: #fff;
      border-bottom: 2px solid #f0f0f0;
      padding: 1rem 1.5rem;
      font-weight: 600;
    }
    .btn-primary {
      background: var(--brand);
      border-color: var(--brand);
    }
    .btn-primary:hover {
      background: #e66a00;
      border-color: #e66a00;
    }
    .stats-card {
      border-left: 4px solid var(--brand);
    }
  </style>
  @yield('styles')
</head>
<body>
  <div class="sidebar">
    <div class="brand">
      <i class="bi bi-flower1"></i> Admin Panel
    </div>
    <nav class="nav">
      <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="bi bi-bag-check"></i> Orders
      </a>
      <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="bi bi-flower2"></i> Products
      </a>
      <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i> Categories
      </a>
      <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Customers
      </a>
      <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> Staff
      </a>
      <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
        <i class="bi bi-image"></i> Banners
      </a>
      <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
        <i class="bi bi-newspaper"></i> News
      </a>
      <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i> Reports
      </a>
      <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">
      <a href="{{ route('home') }}" class="nav-link">
        <i class="bi bi-house"></i> View Site
      </a>
      <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="nav-link btn btn-link text-start w-100" style="border: none;">
          <i class="bi bi-box-arrow-right"></i> Logout
        </button>
      </form>
    </nav>
  </div>

  <div class="main-content">
    <div class="topbar">
      <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
      <div>
        <span class="text-muted">{{ auth()->user()->name }}</span>
      </div>
    </div>

    <div class="content-wrapper">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>
