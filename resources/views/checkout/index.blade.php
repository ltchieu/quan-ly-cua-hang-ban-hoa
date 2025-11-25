@extends('layouts.app')
@section('title','Thanh toán')
@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h4 class="mb-0"><i class="bi bi-bag-check me-2"></i>Thông tin giao hàng</h4>
      </div>
      <div class="card-body">
        <form action="{{ route('checkout.store') }}" method="post" id="checkoutForm">
          @csrf

          <!-- Customer Info -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
              <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', auth()->user()->name) }}" required>
              @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
              <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" required>
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-12">
              <label class="form-label fw-bold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
              <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', auth()->user()->address) }}" required>
              @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-12">
              <label class="form-label fw-bold">Ghi chú</label>
              <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
              @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <!-- Payment Method Selection -->
          <div class="card border-0 bg-light p-3 mb-4">
            <h5 class="mb-3 fw-bold"><i class="bi bi-credit-card me-2"></i>Phương thức thanh toán</h5>
            
            <div class="form-check mb-3 p-3 border rounded" style="background: white; border: 2px solid transparent; transition: all 0.3s;">
              <input class="form-check-input payment-method" type="radio" name="payment_method" id="cod" value="cod" checked>
              <label class="form-check-label ms-2 flex-grow-1" for="cod">
                <strong><i class="bi bi-cash-coin me-2" style="color: #28a745;"></i>Thanh toán khi nhận hàng (COD)</strong>
                <p class="mb-0 text-muted small mt-1">Bạn có thể thanh toán trực tiếp cho nhân viên giao hàng</p>
              </label>
            </div>



            <div class="form-check mb-3 p-3 border rounded" style="background: white; border: 2px solid transparent; transition: all 0.3s;">
              <input class="form-check-input payment-method" type="radio" name="payment_method" id="vnpay" value="vnpay">
              <label class="form-check-label ms-2 flex-grow-1" for="vnpay">
                <strong><i class="bi bi-bank me-2" style="color: #1434cb;"></i>VNPay</strong>
                <p class="mb-0 text-muted small mt-1">Thanh toán qua cổng VNPay hỗ trợ nhiều hình thức</p>
              </label>
            </div>
          </div>

          <div class="text-end">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-2">
              <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
            <button type="submit" class="btn btn-brand">
              <i class="bi bi-check-circle me-1"></i>Tiếp tục
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Order Summary Sidebar -->
  <div class="col-lg-4">
    <div class="card shadow-sm sticky-top" style="top: 100px;">
      <div class="card-header bg-light">
        <h5 class="mb-0">Đơn hàng của bạn</h5>
      </div>
      <div class="card-body" style="max-height: 400px; overflow-y: auto;">
        @foreach($cart as $pid => $item)
          <div class="d-flex gap-2 mb-3 pb-3 border-bottom">
            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/60' }}" alt="{{ $item['name'] }}" 
                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            <div class="flex-grow-1">
              <p class="mb-1 small"><strong>{{ $item['name'] }}</strong></p>
              <p class="mb-0 text-muted small">x{{ $item['qty'] }}</p>
            </div>
            <p class="mb-0 text-end fw-bold" style="color: #ff7a00;">
              {{ number_format($item['price'] * $item['qty'], 0, '.', ',') }} ₫
            </p>
          </div>
        @endforeach
      </div>
      <div class="card-footer bg-light">
        <div class="d-flex justify-content-between mb-2">
          <span>Tạm tính:</span>
          <span>{{ number_format($total, 0, '.', ',') }} ₫</span>
        </div>
        @php
          $discount = session()->get('applied_voucher.discount', 0);
          $finalTotal = $total - $discount;
        @endphp
        @if($discount > 0)
          <div class="d-flex justify-content-between mb-2 text-danger">
            <span>Giảm giá:</span>
            <span>-{{ number_format($discount, 0, '.', ',') }} ₫</span>
          </div>
        @endif
        <div class="d-flex justify-content-between mb-2">
          <span>Phí giao hàng:</span>
          <span>Miễn phí</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
          <strong>Tổng cộng:</strong>
          <strong style="color: #ff7a00; font-size: 1.2rem;">
            {{ number_format($finalTotal, 0, '.', ',') }} ₫
          </strong>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .form-check-input:checked ~ label {
    color: #ff7a00;
  }
  
  .form-check-input:checked ~ label strong {
    color: #ff7a00;
  }
  
  .payment-method:checked ~ label ~ div {
    border-color: #ff7a00 !important;
    background: #fff8f0 !important;
  }
  
  .form-check {
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .form-check:has(.payment-method:checked) {
    background-color: #fff8f0 !important;
    border-color: #ff7a00 !important;
  }
</style>
@endsection

