@extends('layouts.app')

@section('title', 'Thanh toán thất bại')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <!-- Error Banner -->
    <div class="alert alert-danger text-center py-4 mb-4" style="background-color: #f8d7da; border: 2px solid #dc3545;">
      <h2 class="text-danger mb-2">
        <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
      </h2>
      <h3 class="text-danger mb-2">Thanh toán thất bại</h3>
      <p class="text-danger mb-0">Giao dịch không được hoàn thành.</p>
    </div>

    <!-- Error Details Card -->
    <div class="card shadow">
      <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Chi tiết lỗi</h5>
      </div>
      <div class="card-body">
        <div class="mb-3 pb-3 border-bottom">
          <h6 class="mb-2">Lý do có thể gây ra lỗi:</h6>
          <ul class="mb-0 small">
            <li>Tài khoản/ví thanh toán không đủ số dư.</li>
            <li>Hết hạn thẻ hoặc thông tin thẻ không chính xác.</li>
            <li>Kết nối internet không ổn định.</li>
            <li>Giao dịch bị từ chối bởi ngân hàng.</li>
            <li>Quá thời gian cho phép.</li>
          </ul>
        </div>

        <div class="alert alert-info small">
          <i class="bi bi-info-circle me-2"></i>
          @if(session('error_message'))
            {{ session('error_message') }}
          @else
            Đã có lỗi xảy ra trong quá trình xử lý thanh toán.
          @endif
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-2 justify-content-center flex-wrap">
      <a href="{{ route('cart.index') }}" class="btn btn-warning">
        <i class="bi bi-arrow-counterclockwise me-2"></i>Quay lại giỏ hàng
      </a>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary">
        <i class="bi bi-house me-2"></i>Về trang chủ
      </a>
    </div>

    <!-- Support Info -->
    <div class="card mt-4 bg-light">
      <div class="card-body">
        <h6 class="mb-3 fw-bold">Cần hỗ trợ?</h6>
        <p class="mb-1 small">
          <i class="bi bi-telephone me-2" style="color: #ff7a00;"></i>
          <strong>Hotline:</strong> 0900 090 100
        </p>
        <p class="mb-1 small">
          <i class="bi bi-envelope me-2" style="color: #ff7a00;"></i>
          <strong>Email:</strong> support@flowershop.vn
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
