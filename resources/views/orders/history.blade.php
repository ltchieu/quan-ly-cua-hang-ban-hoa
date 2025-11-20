@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
      <li class="breadcrumb-item active">Lịch sử đơn hàng</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header bg-white border-bottom">
            <h4 class="mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</h4>
          </div>

          <!-- Status Tabs (Shopee-style) -->
          <div class="card-body pb-0">
            <ul class="nav nav-underline" role="tablist">
              <li class="nav-item">
                <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'all', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'all' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Tất cả <span class="badge bg-secondary ms-2">{{ $allCount }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'pending', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'pending' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Chờ xác nhận <span class="badge bg-warning ms-2">{{ $statusCounts['pending'] ?? 0 }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status === 'processing' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'processing', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'processing' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Đang xử lý <span class="badge bg-info ms-2">{{ $statusCounts['processing'] ?? 0 }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status === 'shipped' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'shipped', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'shipped' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Đã gửi <span class="badge bg-primary ms-2">{{ $statusCounts['shipped'] ?? 0 }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status === 'delivered' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'delivered', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'delivered' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Đã giao <span class="badge bg-success ms-2">{{ $statusCounts['delivered'] ?? 0 }}</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}" 
                   href="{{ route('orders.index', ['status' => 'cancelled', 'sort' => $sortBy]) }}"
                   style="{{ $status === 'cancelled' ? 'color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Đã hủy <span class="badge bg-danger ms-2">{{ $statusCounts['cancelled'] ?? 0 }}</span>
                </a>
              </li>
            </ul>
          </div>

          <!-- Sort Options -->
          <div class="card-body border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span>Sắp xếp:</span>
              <div>
                <a href="{{ route('orders.index', ['status' => $status, 'sort' => 'latest']) }}" 
                   class="btn btn-sm {{ $sortBy === 'latest' ? 'btn-brand' : 'btn-outline-secondary' }}"
                   style="{{ $sortBy === 'latest' ? 'background-color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Mới nhất
                </a>
                <a href="{{ route('orders.index', ['status' => $status, 'sort' => 'oldest']) }}" 
                   class="btn btn-sm {{ $sortBy === 'oldest' ? 'btn-brand' : 'btn-outline-secondary' }}"
                   style="{{ $sortBy === 'oldest' ? 'background-color: #ff7a00; border-color: #ff7a00;' : '' }}">
                  Cũ nhất
                </a>
              </div>
            </div>
          </div>

          <!-- Orders List -->
          <div class="card-body border-top">
            @if($orders->count() > 0)
              <div class="space-y-3">
                @foreach($orders as $order)
                  @php
                    $statusColors = [
                      'pending' => ['bg' => 'warning', 'text' => 'Chờ xác nhận'],
                      'processing' => ['bg' => 'info', 'text' => 'Đang xử lý'],
                      'shipped' => ['bg' => 'primary', 'text' => 'Đã gửi'],
                      'delivered' => ['bg' => 'success', 'text' => 'Đã giao'],
                      'cancelled' => ['bg' => 'danger', 'text' => 'Đã hủy'],
                    ];
                    $statusInfo = $statusColors[$order->status] ?? ['bg' => 'secondary', 'text' => $order->status];
                  @endphp
                  
                  <div class="order-card border rounded p-3 mb-3" style="border: 1px solid #e0e0e0; transition: all 0.3s;">
                    <!-- Order Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="mb-1">Đơn hàng <strong>#{{ $order->id }}</strong></h6>
                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                      </div>
                      <span class="badge bg-{{ $statusInfo['bg'] }}">{{ $statusInfo['text'] }}</span>
                    </div>

                    <!-- Order Items (Mini) -->
                    <div class="mb-3">
                      @foreach($order->items->take(2) as $item)
                        <div class="d-flex gap-3 mb-2">
                          <img src="{{ $item->product?->image ?? 'https://via.placeholder.com/60' }}" 
                               alt="{{ $item->product?->name }}" 
                               style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                          <div class="flex-grow-1">
                            <p class="mb-1 text-truncate">
                              <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none text-dark">
                                {{ $item->product?->name ?? 'Sản phẩm không tồn tại' }}
                              </a>
                            </p>
                            <small class="text-muted">x{{ $item->quantity }}</small>
                          </div>
                          <p class="mb-0 fw-bold">{{ number_format($item->price * $item->quantity, 0, '.', ',') }} ₫</p>
                        </div>
                      @endforeach

                      @if($order->items->count() > 2)
                        <small class="text-muted">...và {{ $order->items->count() - 2 }} sản phẩm khác</small>
                      @endif
                    </div>

                    <!-- Order Footer -->
                    <div class="border-top pt-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <p class="mb-0 text-muted">Tổng cộng:</p>
                          <p class="mb-0 fw-bold" style="color: #ff7a00; font-size: 1.1rem;">
                            {{ number_format($order->total, 0, '.', ',') }} ₫
                          </p>
                        </div>
                        <div>
                          <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-eye me-1"></i> Xem chi tiết
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Pagination -->
              <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
              </div>
            @else
              <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Không có đơn hàng</p>
                <a href="{{ route('products.index') }}" class="btn btn-brand">
                  <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .order-card:hover {
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-color: #ff7a00 !important;
    }
    
    .nav-underline .nav-link {
      border-bottom: 2px solid transparent;
      color: #666;
      padding-bottom: 12px;
      transition: all 0.3s;
    }
    
    .nav-underline .nav-link:hover {
      color: #ff7a00;
      border-bottom-color: #ff7a00;
    }
    
    .nav-underline .nav-link.active {
      color: #ff7a00;
      border-bottom-color: #ff7a00;
    }
  </style>
@endsection

