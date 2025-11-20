@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Lịch sử đơn hàng</a></li>
      <li class="breadcrumb-item active">Đơn #{{ $order->id }}</li>
    </ol>
  </nav>
@endsection

@section('content')
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

  <div class="row">
    <div class="col-lg-8">
      <!-- Order Status Card -->
      <div class="card shadow-sm mb-3">
        <div class="card-body text-center py-4">
          <h5 class="mb-3">
            <span class="badge bg-{{ $statusInfo['bg'] }} p-2" style="font-size: 1rem;">
              {{ $statusInfo['text'] }}
            </span>
          </h5>
          <p class="text-muted mb-0">Đơn hàng <strong>#{{ $order->id }}</strong> • {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
      </div>

      <!-- Products -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
          <h6 class="mb-0"><i class="bi bi-bag me-2"></i>Sản phẩm</h6>
        </div>
        <div class="card-body">
          @foreach($order->items as $item)
            <div class="d-flex gap-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="padding-bottom: 1.5rem;">
              <img src="{{ $item->product?->image ?? 'https://via.placeholder.com/100' }}" 
                   alt="{{ $item->product?->name }}" 
                   style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
              
              <div class="flex-grow-1">
                <h6 class="mb-1">
                  <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none text-dark">
                    {{ $item->product?->name ?? 'Sản phẩm không tồn tại' }}
                  </a>
                </h6>
                <p class="text-muted mb-2" style="font-size: 0.9rem;">Số lượng: <strong>{{ $item->quantity }}</strong></p>
                <p class="mb-0" style="color: #ff7a00; font-weight: bold; font-size: 1rem;">
                  {{ number_format($item->price, 0, '.', ',') }} ₫
                </p>
              </div>

              <div class="text-end">
                <p class="text-muted mb-2" style="font-size: 0.9rem;">Thành tiền</p>
                <p class="mb-0 fw-bold" style="color: #ff7a00; font-size: 1.1rem;">
                  {{ number_format($item->price * $item->quantity, 0, '.', ',') }} ₫
                </p>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Shipping Info -->
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Địa chỉ giao hàng</h6>
        </div>
        <div class="card-body">
          <p class="mb-1"><strong>{{ $order->full_name }}</strong></p>
          <p class="mb-1 text-muted"><i class="bi bi-telephone me-2"></i>{{ $order->phone }}</p>
          <p class="mb-0 text-muted"><i class="bi bi-house me-2"></i>{{ $order->address }}</p>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Price Summary -->
      <div class="card shadow-sm sticky-top" style="top: 20px;">
        <div class="card-header bg-light">
          <h6 class="mb-0">Thông tin thanh toán</h6>
        </div>
        <div class="card-body">
          @php
            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
            $discount = 0; // Adjust if you have discount column
            $shipping = 0; // Adjust if you have shipping fee column
            $total = $order->total;
          @endphp

          <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
            <span>Tạm tính:</span>
            <span>{{ number_format($subtotal, 0, '.', ',') }} ₫</span>
          </div>

          @if($discount > 0)
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom text-success">
              <span>Giảm giá:</span>
              <span>-{{ number_format($discount, 0, '.', ',') }} ₫</span>
            </div>
          @endif

          @if($shipping > 0)
            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
              <span>Phí giao hàng:</span>
              <span>{{ number_format($shipping, 0, '.', ',') }} ₫</span>
            </div>
          @endif

          <div class="d-flex justify-content-between pt-2">
            <strong>Tổng cộng:</strong>
            <strong style="color: #ff7a00; font-size: 1.3rem;">
              {{ number_format($total, 0, '.', ',') }} ₫
            </strong>
          </div>
        </div>
      </div>

      <!-- Payment Method -->
      <div class="card shadow-sm mt-3">
        <div class="card-header bg-light">
          <h6 class="mb-0"><i class="bi bi-credit-card me-2"></i>Phương thức thanh toán</h6>
        </div>
        <div class="card-body">
          <p class="mb-0">
            @if($order->payment_method === 'cod')
              <i class="bi bi-cash-coin me-2"></i><strong>Thanh toán khi nhận hàng</strong>
            @elseif($order->payment_method === 'transfer')
              <i class="bi bi-bank me-2"></i><strong>Chuyển khoản ngân hàng</strong>
            @elseif($order->payment_method === 'wallet')
              <i class="bi bi-wallet2 me-2"></i><strong>Ví điện tử</strong>
            @else
              {{ ucfirst($order->payment_method) }}
            @endif
          </p>
        </div>
      </div>

      <!-- Order Notes -->
      @if($order->note)
        <div class="card shadow-sm mt-3">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Ghi chú</h6>
          </div>
          <div class="card-body">
            <p class="mb-0 text-muted">{{ $order->note }}</p>
          </div>
        </div>
      @endif

      <!-- Back Button -->
      <div class="mt-3">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
          <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
      </div>
    </div>
  </div>
@endsection
