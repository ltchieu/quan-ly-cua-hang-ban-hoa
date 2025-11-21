@extends('layouts.app')
@section('title','Liên hệ chúng tôi')

@section('content')
<div class="py-5">
  <div class="container">
    <!-- Header -->
    <div class="row mb-5">
      <div class="col-12">
        <h1 class="display-5 fw-bold mb-2">Liên hệ chúng tôi</h1>
        <p class="lead text-muted">Gửi yêu cầu hỗ trợ của bạn đến đội ngũ khách hàng của chúng tôi</p>
      </div>
    </div>

    <!-- Contact Content -->
    <div class="row">
      <!-- Contact Information Sidebar -->
      <div class="col-lg-4 mb-5 mb-lg-0">
        <!-- Email -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <div class="flex-shrink-0">
                <i class="bi bi-envelope" style="font-size:1.5rem;color:#ff7a00;"></i>
              </div>
              <div class="ms-3">
                <h5 class="card-title fw-bold">Email</h5>
                <p class="card-text">support@flowershop.com</p>
                <p class="small text-muted">Phản hồi trong 24 giờ</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Phone -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <div class="flex-shrink-0">
                <i class="bi bi-telephone" style="font-size:1.5rem;color:#ff7a00;"></i>
              </div>
              <div class="ms-3">
                <h5 class="card-title fw-bold">Điện thoại</h5>
                <p class="card-text">1900 1234</p>
                <p class="small text-muted">Thứ 2 - Chủ nhật, 8:00 - 22:00</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Address -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <div class="flex-shrink-0">
                <i class="bi bi-geo-alt" style="font-size:1.5rem;color:#ff7a00;"></i>
              </div>
              <div class="ms-3">
                <h5 class="card-title fw-bold">Địa chỉ</h5>
                <p class="card-text">123 Đường Hoa<br/>Quận 1, TP.HCM</p>
                <p class="small text-muted">Thứ 2 - Chủ nhật, 9:00 - 18:00</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title fw-bold mb-3">Liên kết nhanh</h5>
            <div class="d-flex flex-column gap-2">
              <a href="{{ route('support.faq') }}" class="text-decoration-none" style="color:#ff7a00;">
                <i class="bi bi-question-circle me-2"></i>Xem câu hỏi thường gặp
              </a>
              <a href="{{ route('support.tickets') }}" class="text-decoration-none" style="color:#ff7a00;">
                <i class="bi bi-ticket-detailed me-2"></i>Xem yêu cầu của tôi
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4 p-md-5">
            <h2 class="card-title display-6 fw-bold mb-4">Gửi yêu cầu hỗ trợ</h2>

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
              <strong><i class="bi bi-exclamation-circle me-2"></i>Vui lòng kiểm tra lại thông tin:</strong>
              <ul class="list-unstyled mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li class="small">{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('support.store') }}" method="POST" class="needs-validation" novalidate>
              @csrf

              <!-- Subject -->
              <div class="mb-3">
                <label for="subject" class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  class="form-control @error('subject') is-invalid @enderror" 
                  id="subject" 
                  name="subject" 
                  placeholder="Nhập tiêu đề yêu cầu"
                  value="{{ old('subject') }}"
                  required>
                @error('subject')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <!-- Category -->
              <div class="mb-3">
                <label for="category" class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                <select 
                  class="form-select @error('category') is-invalid @enderror" 
                  id="category" 
                  name="category"
                  required>
                  <option value="">-- Chọn danh mục --</option>
                  <option value="order" {{ old('category') === 'order' ? 'selected' : '' }}>
                    <i class="bi bi-cart"></i> Đơn hàng
                  </option>
                  <option value="product" {{ old('category') === 'product' ? 'selected' : '' }}>
                    <i class="bi bi-flower1"></i> Sản phẩm
                  </option>
                  <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>
                    <i class="bi bi-credit-card"></i> Thanh toán
                  </option>
                  <option value="shipping" {{ old('category') === 'shipping' ? 'selected' : '' }}>
                    <i class="bi bi-truck"></i> Giao hàng
                  </option>
                  <option value="return" {{ old('category') === 'return' ? 'selected' : '' }}>
                    <i class="bi bi-arrow-repeat"></i> Đổi trả
                  </option>
                  <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>
                    <i class="bi bi-question-circle"></i> Khác
                  </option>
                </select>
                @error('category')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <!-- Message -->
              <div class="mb-3">
                <label for="message" class="form-label fw-semibold">Chi tiết vấn đề <span class="text-danger">*</span></label>
                <textarea 
                  class="form-control @error('message') is-invalid @enderror" 
                  id="message" 
                  name="message" 
                  rows="8"
                  placeholder="Mô tả chi tiết vấn đề của bạn (tối thiểu 10 ký tự)..."
                  minlength="10"
                  maxlength="2000"
                  required>{{ old('message') }}</textarea>
                <small class="d-block mt-2 text-muted">
                  <span class="char-count">0</span>/2000 ký tự
                </small>
                @error('message')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <!-- Submit Buttons -->
              <div class="d-flex gap-3 mb-3">
                <button 
                  type="submit" 
                  class="btn btn-brand btn-lg flex-grow-1">
                  <i class="bi bi-send me-2"></i>Gửi yêu cầu
                </button>
                <button 
                  type="reset" 
                  class="btn btn-outline-secondary btn-lg">
                  <i class="bi bi-arrow-counterclockwise me-2"></i>Xóa
                </button>
              </div>

              <!-- Info Alert -->
              <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Lưu ý:</strong> Chúng tôi sẽ phản hồi yêu cầu của bạn trong 24 giờ. Vui lòng kiểm tra email để cập nhật.
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .form-control:focus,
  .form-select:focus {
    border-color: #ff7a00;
    box-shadow: 0 0 0 0.2rem rgba(255, 122, 0, 0.25);
  }
  .btn-brand {
    background: var(--brand);
    border: 0;
    color: #fff;
  }
  .btn-brand:hover {
    background: var(--brand-2);
    color: #fff;
  }
</style>

<script>
  // Character counter
  const messageField = document.getElementById('message');
  const charCount = document.querySelector('.char-count');
  
  messageField.addEventListener('input', function() {
    charCount.textContent = this.value.length;
  });

  // Bootstrap form validation
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      const forms = document.querySelectorAll('.needs-validation');
      Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();
</script>

@endsection
