@extends('layouts.app')
@section('title','Câu hỏi thường gặp')

@section('content')
<div class="py-5">
  <div class="container">
    <!-- Header -->
    <div class="row mb-5">
      <div class="col-lg-8 mx-auto">
        <div class="text-center mb-4">
          <h1 class="display-5 fw-bold mb-2">Câu Hỏi Thường Gặp</h1>
          <p class="lead text-muted">
            Tìm hiểu thêm thông tin về dịch vụ của chúng tôi.
            <a href="{{ route('support.contact') }}" class="text-decoration-none fw-semibold">Liên hệ với chúng tôi</a>
          </p>
        </div>
      </div>
    </div>

    <!-- FAQ Content -->
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <!-- Category Tabs -->
        <div class="mb-4" id="categoryFilter">
          <ul class="nav nav-pills gap-2 flex-wrap" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="btn btn-sm btn-outline-secondary active category-btn" type="button" data-category="all" role="tab">
                <i class="bi bi-check-circle me-2"></i>Tất cả
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="btn btn-sm btn-outline-secondary category-btn" type="button" data-category="order" role="tab">
                <i class="bi bi-box me-2"></i>Đơn hàng
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="btn btn-sm btn-outline-secondary category-btn" type="button" data-category="product" role="tab">
                <i class="bi bi-flower1 me-2"></i>Sản phẩm
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="btn btn-sm btn-outline-secondary category-btn" type="button" data-category="payment" role="tab">
                <i class="bi bi-credit-card me-2"></i>Thanh toán
              </button>
            </li>
          </ul>
        </div>

        <!-- Accordion FAQ -->
        <div class="accordion" id="faqAccordion">
          @foreach($faqs as $index => $faq)
          <div class="accordion-item border-0 mb-3 rounded shadow-sm faq-item" data-category="{{ $faq['category'] }}">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $index }}" aria-expanded="false">
                <span class="badge bg-danger me-3 d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:1rem;">
                  <i class="bi bi-question"></i>
                </span>
                {{ $faq['question'] }}
              </button>
            </h2>
            <div id="faq{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg">
                {!! nl2br(e($faq['answer'])) !!}
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Still Have Questions Card -->
        <div class="card mt-5 border-0 shadow-sm" style="background:linear-gradient(135deg, #fff3e6 0%, #ffe0c2 100%);">
          <div class="card-body p-5 text-center">
            <div class="mb-4">
              <i class="bi bi-chat-left-text" style="font-size:3rem;color:#ff7a00;"></i>
            </div>
            <h5 class="card-title fw-bold text-dark mb-2">Bạn vẫn có câu hỏi?</h5>
            <p class="card-text text-muted mb-4">
              Không tìm thấy câu trả lời bạn cần? Hãy liên hệ với chúng tôi ngay.
            </p>
            <a href="{{ route('support.contact') }}" class="btn btn-brand btn-lg">
              <i class="bi bi-send me-2"></i>Gửi tin nhắn
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .accordion-button:not(.collapsed) {
    background-color: #fff3e6;
    color: #ff7a00;
    box-shadow: none;
  }
  .accordion-button:focus {
    border-color: #ff7a00;
    box-shadow: 0 0 0 0.25rem rgba(255, 122, 0, 0.25);
  }
  .badge-danger {
    background-color: #ff7a00 !important;
  }
  .btn-outline-secondary {
    color: #6c757d;
    border-color: #dee2e6;
  }
  .btn-outline-secondary:hover {
    background-color: #ff7a00;
    border-color: #ff7a00;
    color: #fff;
  }
  .btn-outline-secondary.active {
    background-color: #ff7a00;
    border-color: #ff7a00;
    color: #fff;
  }
  .faq-item.d-none {
    display: none !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const faqItems = document.querySelectorAll('.faq-item');

    categoryButtons.forEach(button => {
      button.addEventListener('click', function() {
        const selectedCategory = this.getAttribute('data-category');

        // Update active button
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        // Filter FAQ items
        faqItems.forEach(item => {
          const itemCategory = item.getAttribute('data-category');
          
          if (selectedCategory === 'all' || itemCategory === selectedCategory) {
            item.classList.remove('d-none');
          } else {
            item.classList.add('d-none');
          }
        });
      });
    });
  });
</script>

@endsection