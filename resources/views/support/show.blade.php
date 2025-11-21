@extends('layouts.app')
@section('title','Chi tiết yêu cầu hỗ trợ')

@section('content')
<div class="py-5">
  <div class="container">
    <!-- Header -->
    <div class="mb-5">
      <a href="{{ route('support.tickets') }}" class="btn btn-link p-0" style="color:#ff7a00;">
        <i class="bi bi-arrow-left me-2"></i>Quay lại
      </a>
      <h1 class="display-5 fw-bold mt-3 mb-2">{{ $ticket->subject }}</h1>
      <p class="text-muted">Mã yêu cầu: <code class="fw-semibold">{{ $ticket->ticket_number }}</code></p>
    </div>

    <!-- Status & Info Cards -->
    <div class="row g-3 mb-5">
      <!-- Status -->
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <small class="text-muted d-block mb-2">Trạng thái</small>
            <div class="fw-bold" style="font-size:1.25rem;
              @if($ticket->status === 'open') color:#dc3545;
              @elseif($ticket->status === 'in_progress') color:#ffc107;
              @elseif($ticket->status === 'resolved') color:#198754;
              @else color:#6c757d;
              @endif
            ">
              @if($ticket->status === 'open')
                <i class="bi bi-clock me-1"></i>Mở
              @elseif($ticket->status === 'in_progress')
                <i class="bi bi-hourglass-split me-1"></i>Đang xử lý
              @elseif($ticket->status === 'resolved')
                <i class="bi bi-check-circle me-1"></i>Đã giải quyết
              @else
                <i class="bi bi-x-circle me-1"></i>Đã đóng
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Category -->
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <small class="text-muted d-block mb-2">Danh mục</small>
            <p class="fw-bold mb-0">
              @if($ticket->category === 'order')
                <i class="bi bi-cart me-1"></i>Đơn hàng
              @elseif($ticket->category === 'payment')
                <i class="bi bi-credit-card me-1"></i>Thanh toán
              @elseif($ticket->category === 'shipping')
                <i class="bi bi-truck me-1"></i>Giao hàng
              @elseif($ticket->category === 'return')
                <i class="bi bi-arrow-repeat me-1"></i>Đổi trả
              @elseif($ticket->category === 'product')
                <i class="bi bi-flower1 me-1"></i>Sản phẩm
              @else
                <i class="bi bi-question-circle me-1"></i>Khác
              @endif
            </p>
          </div>
        </div>
      </div>

      <!-- Created Date -->
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <small class="text-muted d-block mb-2">Gửi lúc</small>
            <p class="fw-bold mb-1">{{ $ticket->created_at->format('d/m/Y') }}</p>
            <small class="text-muted">{{ $ticket->created_at->format('H:i') }}</small>
          </div>
        </div>
      </div>

      <!-- Response Date -->
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <small class="text-muted d-block mb-2">Phản hồi lúc</small>
            @if($ticket->responded_at)
              <p class="fw-bold mb-1 text-success">{{ $ticket->responded_at->format('d/m/Y') }}</p>
              <small class="text-muted">{{ $ticket->responded_at->format('H:i') }}</small>
            @else
              <p class="fw-bold mb-0 text-secondary">Chưa phản hồi</p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Messages -->
    <div class="card border-0 shadow-sm mb-5">
      <!-- User Message -->
      <div class="card-body p-4">
        <div class="d-flex gap-3 mb-4">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->user->name) }}&background=random" alt="{{ $ticket->user->name }}" class="rounded-circle" style="width:48px;height:48px;">
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="fw-bold mb-0">{{ $ticket->user->name }}</h5>
              <small class="text-muted">{{ $ticket->created_at->diffForHumans() }}</small>
            </div>
            <small class="text-muted d-block mb-2">Yêu cầu hỗ trợ</small>
            <div class="bg-light p-3 rounded border-start border-danger" style="border-width:4px !important;">
              <p class="mb-0 text-dark" style="white-space:pre-wrap;">{{ $ticket->message }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Response -->
      @if($ticket->admin_response)
      <div class="card-body p-4 border-top bg-light">
        <div class="d-flex gap-3">
          <div style="width:48px;height:48px;" class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center flex-shrink-0">
            <i class="bi bi-headset fw-bold"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="fw-bold mb-0">Đội hỗ trợ khách hàng</h5>
              <small class="text-muted">{{ $ticket->responded_at->diffForHumans() }}</small>
            </div>
            <small class="text-muted d-block mb-2">Phản hồi chính thức</small>
            <div class="bg-white p-3 rounded border-start border-info" style="border-width:4px !important;">
              <p class="mb-0 text-dark" style="white-space:pre-wrap;">{{ $ticket->admin_response }}</p>
            </div>
          </div>
        </div>
      </div>
      @else
      <div class="card-body p-5 text-center border-top">
        <i class="bi bi-clock" style="font-size:3rem;color:#ccc;"></i>
        <p class="text-muted mt-3 mb-2">Chúng tôi đang xử lý yêu cầu của bạn. Vui lòng đợi trong ít phút.</p>
        <small class="text-muted">Thường phản hồi trong 24 giờ</small>
      </div>
      @endif
    </div>

    <!-- Actions -->
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h5 class="card-title fw-bold mb-4">Bạn cần giúp gì thêm không?</h5>
        <div class="row g-2">
          <div class="col-md-4">
            <a href="{{ route('support.contact') }}" class="btn btn-brand w-100">
              <i class="bi bi-plus-lg me-2"></i>Gửi yêu cầu mới
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('support.faq') }}" class="btn btn-outline-secondary w-100" style="color:#ff7a00;border-color:#ff7a00;">
              <i class="bi bi-question-circle me-2"></i>Xem FAQ
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('support.tickets') }}" class="btn btn-outline-secondary w-100" style="color:#ff7a00;border-color:#ff7a00;">
              <i class="bi bi-list me-2"></i>Tất cả yêu cầu
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .btn-outline-secondary:hover {
    background-color: #ff7a00 !important;
    border-color: #ff7a00 !important;
    color: #fff !important;
  }
</style>
@endsection
