@extends('layouts.app')
@section('title','Yêu cầu hỗ trợ của tôi')

@section('content')
<div class="py-5">
  <div class="container">
    <!-- Header -->
    <div class="row mb-5">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="display-5 fw-bold mb-2">Yêu cầu hỗ trợ của tôi</h1>
            <p class="lead text-muted">Theo dõi trạng thái các yêu cầu của bạn</p>
          </div>
          <a href="{{ route('support.contact') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg me-2"></i>Gửi yêu cầu mới
          </a>
        </div>
      </div>
    </div>

    <!-- Tickets Content -->
    @if ($tickets->isEmpty())
    <div class="card border-0 shadow-sm">
      <div class="card-body p-5 text-center py-md-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
        <h3 class="card-title fw-bold mt-3 mb-2">Chưa có yêu cầu nào</h3>
        <p class="text-muted mb-4">Bạn chưa gửi yêu cầu hỗ trợ nào. Nếu bạn cần giúp đỡ, vui lòng liên hệ với chúng tôi.</p>
        <a href="{{ route('support.contact') }}" class="btn btn-brand">
          <i class="bi bi-envelope me-2"></i>Gửi yêu cầu ngay
        </a>
      </div>
    </div>
    @else
    <div class="row g-3 mb-5">
      @foreach ($tickets as $ticket)
      <div class="col-12">
        <div class="card border-0 shadow-sm cursor-pointer" onclick="window.location.href='{{ route('support.show', $ticket->id) }}'" style="cursor:pointer;">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-grow-1">
                <!-- Badges Row -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge bg-light text-dark">{{ $ticket->ticket_number }}</span>
                  
                  <!-- Category Badge -->
                  <span class="badge
                    @if($ticket->category === 'order') bg-info
                    @elseif($ticket->category === 'payment') bg-success
                    @elseif($ticket->category === 'shipping') bg-purple text-white
                    @elseif($ticket->category === 'return') bg-warning text-dark
                    @elseif($ticket->category === 'product') bg-danger
                    @else bg-secondary
                    @endif
                  ">
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
                  </span>
                  
                  <!-- Status Badge -->
                  <span class="badge
                    @if($ticket->status === 'open') bg-danger
                    @elseif($ticket->status === 'in_progress') bg-warning text-dark
                    @elseif($ticket->status === 'resolved') bg-success
                    @else bg-secondary
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
                  </span>
                </div>

                <h5 class="card-title fw-bold mb-2">{{ $ticket->subject }}</h5>
                <p class="card-text text-muted small mb-3">{{ Str::limit($ticket->message, 100) }}</p>
                
                <small class="text-muted">
                  <i class="bi bi-calendar me-1"></i>{{ $ticket->created_at->format('d/m/Y H:i') }}
                  @if($ticket->responded_at)
                    <span class="text-success ms-3">
                      <i class="bi bi-check me-1"></i>Đã phản hồi: {{ $ticket->responded_at->format('d/m/Y H:i') }}
                    </span>
                  @endif
                </small>
              </div>
              <i class="bi bi-chevron-right text-muted ms-3"></i>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
      {{ $tickets->links() }}
    </div>
    @endif

    <!-- Quick Links -->
    <div class="row g-3 mt-5">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <i class="bi bi-question-circle" style="font-size:2.5rem;color:#ff7a00;"></i>
            <h5 class="card-title fw-bold mt-3 mb-2">Câu hỏi thường gặp</h5>
            <p class="card-text small text-muted mb-3">Tìm câu trả lời cho các câu hỏi thường gặp</p>
            <a href="{{ route('support.faq') }}" class="btn btn-sm btn-outline-secondary" style="color:#ff7a00;border-color:#ff7a00;">
              Xem FAQ →
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <i class="bi bi-envelope" style="font-size:2.5rem;color:#ff7a00;"></i>
            <h5 class="card-title fw-bold mt-3 mb-2">Liên hệ chúng tôi</h5>
            <p class="card-text small text-muted mb-3">Không tìm được giải pháp? Hãy liên hệ với đội support</p>
            <a href="{{ route('support.contact') }}" class="btn btn-sm btn-outline-secondary" style="color:#ff7a00;border-color:#ff7a00;">
              Gửi yêu cầu →
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <i class="bi bi-telephone" style="font-size:2.5rem;color:#ff7a00;"></i>
            <h5 class="card-title fw-bold mt-3 mb-2">Gọi cho chúng tôi</h5>
            <p class="card-text small text-muted mb-3">1900 1234 - Thứ 2-7, 8:00 - 22:00</p>
            <a href="tel:19001234" class="btn btn-sm btn-outline-secondary" style="color:#ff7a00;border-color:#ff7a00;">
              Gọi ngay →
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
