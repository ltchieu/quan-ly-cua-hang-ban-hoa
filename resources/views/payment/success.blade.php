@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <!-- Success Banner -->
    <div class="alert alert-success text-center py-4 mb-4" style="background-color: #d4edda; border: 2px solid #28a745;">
      <h2 class="text-success mb-2">
        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
      </h2>
      <h3 class="text-success mb-0">Thanh toán thành công!</h3>
      <p class="text-success mt-2 mb-0">Cảm ơn bạn đã mua hàng tại FlowerShop</p>
    </div>

    <!-- Invoice Card -->
    <div class="card shadow-lg">
      <div class="card-header" style="background: linear-gradient(135deg, #ff7a00 0%, #ff9a3c 100%); color: white;">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">HÓA ĐƠN</h5>
            <p class="mb-0 small">Mã đơn: <strong>#{{ $order->id }}</strong></p>
          </div>
          <div class="text-end">
            <img src="{{ asset('logo.png') }}" alt="FlowerShop" style="max-height: 50px;">
            <p class="mb-0 small">{{ now()->format('d/m/Y H:i') }}</p>
          </div>
        </div>
      </div>

      <div class="card-body">
        <!-- Store Info -->
        <div class="row mb-4 pb-4 border-bottom">
          <div class="col-md-6">
            <h6 class="mb-2 fw-bold">🌼 FlowerShop</h6>
            <p class="mb-1 small text-muted">
              <i class="bi bi-geo-alt"></i> 123 Đường Hoa, Q.1, TP.HCM
            </p>
            <p class="mb-1 small text-muted">
              <i class="bi bi-telephone"></i> 0900 090 100
            </p>
            <p class="mb-0 small text-muted">
              <i class="bi bi-envelope"></i> hello@flowershop.vn
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <h6 class="mb-2 fw-bold">Thông tin khách hàng</h6>
            <p class="mb-1"><strong>{{ $order->full_name }}</strong></p>
            <p class="mb-1 small text-muted">{{ $order->phone }}</p>
            <p class="mb-0 small text-muted">{{ $order->address }}</p>
          </div>
        </div>

        <!-- Order Details -->
        <div class="row mb-4 pb-4 border-bottom">
          <div class="col-md-6">
            <p class="mb-1"><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p class="mb-0"><strong>Trạng thái:</strong> 
              @if($order->status === 'processing')
                <span class="badge bg-info">Đang xử lý</span>
              @elseif($order->status === 'shipped')
                <span class="badge bg-primary">Đã gửi</span>
              @elseif($order->status === 'delivered')
                <span class="badge bg-success">Đã giao</span>
              @else
                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
              @endif
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="mb-1"><strong>Phương thức thanh toán:</strong>
              @if($order->payment_method === 'cod')
                <span>Thanh toán khi nhận hàng</span>
              @elseif($order->payment_method === 'momo')
                <span class="badge bg-danger">Momo</span>
              @elseif($order->payment_method === 'vnpay')
                <span class="badge bg-primary">VNPay</span>
              @endif
            </p>
            @if($order->paid_at)
              <p class="mb-0"><strong>Thanh toán lúc:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}</p>
            @endif
          </div>
        </div>

        <!-- Products Table -->
        <div class="mb-4">
          <h6 class="mb-3 fw-bold">Sản phẩm đã đặt</h6>
          <div class="table-responsive">
            <table class="table table-sm table-borderless">
              <thead style="background-color: #f8f9fa;">
                <tr>
                  <th>Sản phẩm</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Giá</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td>
                      <strong>{{ $item->product?->name ?? 'Sản phẩm không tồn tại' }}</strong>
                      @if($item->product)
                        <br><small class="text-muted">SKU: {{ $item->product->id }}</small>
                      @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->price, 0, '.', ',') }} ₫</td>
                    <td class="text-end fw-bold">
                      {{ number_format($item->price * $item->quantity, 0, '.', ',') }} ₫
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Summary -->
        <div class="row">
          <div class="col-md-6 offset-md-6">
            <div class="border-top pt-3">
              <div class="d-flex justify-content-between mb-2">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->items->sum(fn($item) => $item->price * $item->quantity), 0, '.', ',') }} ₫</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Phí giao hàng:</span>
                <span>Miễn phí</span>
              </div>
              <div class="border-top pt-3">
                <div class="d-flex justify-content-between" style="font-size: 1.1rem;">
                  <strong>Tổng cộng:</strong>
                  <strong style="color: #ff7a00;">{{ number_format($order->total, 0, '.', ',') }} ₫</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer bg-light text-center">
        <small class="text-muted">
          Cảm ơn bạn đã tin tưởng FlowerShop. Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng.
        </small>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-2 justify-content-center">
      <button class="btn btn-brand" onclick="window.print()">
        <i class="bi bi-printer me-2"></i>In hóa đơn
      </button>
      <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-eye me-2"></i>Xem chi tiết
      </a>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary">
        <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
      </a>
    </div>

    <!-- Additional Info -->
    <div class="alert alert-info mt-4 small">
      <i class="bi bi-info-circle me-2"></i>
      <strong>Thông báo quan trọng:</strong>
      <ul class="mb-0 mt-2">
        <li>Đơn hàng của bạn sẽ được xác nhận trong vòng 24 giờ</li>
        <li>Bạn sẽ nhận được email xác nhận và cập nhật tình trạng giao hàng</li>
        <li>Nếu có thắc mắc, vui lòng liên hệ: 0900 090 100</li>
      </ul>
    </div>
  </div>
</div>

<style>
  @media print {
    body { background: white; }
    .alert, .btn, .d-flex.gap-2, .alert.alert-info { display: none; }
    .card { box-shadow: none; border: 1px solid #ddd; }
  }
</style>
@endsection
