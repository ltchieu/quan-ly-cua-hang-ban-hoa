@extends('layouts.app')
@section('title','Trung tâm hỗ trợ khách hàng')

@section('content')
<div class="support-hub">
  <!-- Hero Section -->
  <div class="hero-section text-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
      <h1 class="display-4 fw-bold text-white mb-3">Trung tâm hỗ trợ khách hàng</h1>
      <p class="lead text-white opacity-75 mb-4">Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7</p>
      
      <!-- Search Box -->
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="input-group input-group-lg shadow">
            <span class="input-group-text bg-white border-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" class="form-control border-0" id="searchHelp" placeholder="Tìm kiếm câu hỏi, đơn hàng...">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions py-5 bg-light">
    <div class="container">
      <div class="row g-4">
        <!-- Order Tracking -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body text-center p-4">
              <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-truck" style="font-size: 2rem; color: white;"></i>
              </div>
              <h4 class="fw-bold mb-3">Tra cứu đơn hàng</h4>
              <p class="text-muted mb-4">Kiểm tra tình trạng và lịch trình giao hàng của bạn</p>
              <button class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#trackOrderModal">
                <i class="bi bi-search me-2"></i>Tra cứu ngay
              </button>
            </div>
          </div>
        </div>

        <!-- Contact Support -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body text-center p-4">
              <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-headset" style="font-size: 2rem; color: white;"></i>
              </div>
              <h4 class="fw-bold mb-3">Liên hệ hỗ trợ</h4>
              <p class="text-muted mb-4">Gửi yêu cầu hỗ trợ, chúng tôi sẽ phản hồi trong 24h</p>
              <a href="{{ route('support.contact') }}" class="btn btn-info btn-lg w-100 text-white">
                <i class="bi bi-envelope me-2"></i>Gửi yêu cầu
              </a>
            </div>
          </div>
        </div>

        <!-- FAQ -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 hover-lift">
            <div class="card-body text-center p-4">
              <div class="icon-circle mb-3 mx-auto" style="width: 80px; height: 80px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-question-circle" style="font-size: 2rem; color: white;"></i>
              </div>
              <h4 class="fw-bold mb-3">Câu hỏi thường gặp</h4>
              <p class="text-muted mb-4">Tìm câu trả lời nhanh cho các câu hỏi phổ biến</p>
              <a href="{{ route('support.faq') }}" class="btn btn-warning btn-lg w-100">
                <i class="bi bi-list-ul me-2"></i>Xem FAQ
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Popular Topics -->
  <div class="popular-topics py-5">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Chủ đề phổ biến</h2>
      <div class="row g-4">
        <div class="col-md-3 col-sm-6">
          <a href="{{ route('support.faq') }}#order" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
              <div class="card-body text-center p-4">
                <i class="bi bi-box-seam mb-3" style="font-size: 2.5rem; color: #667eea;"></i>
                <h5 class="fw-bold">Đơn hàng</h5>
                <p class="text-muted small mb-0">Cách đặt hàng và quản lý đơn</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="{{ route('support.faq') }}#payment" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
              <div class="card-body text-center p-4">
                <i class="bi bi-credit-card mb-3" style="font-size: 2.5rem; color: #4facfe;"></i>
                <h5 class="fw-bold">Thanh toán</h5>
                <p class="text-muted small mb-0">Phương thức và bảo mật</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="{{ route('support.faq') }}#shipping" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
              <div class="card-body text-center p-4">
                <i class="bi bi-truck mb-3" style="font-size: 2.5rem; color: #f5576c;"></i>
                <h5 class="fw-bold">Giao hàng</h5>
                <p class="text-muted small mb-0">Thời gian và phí vận chuyển</p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-3 col-sm-6">
          <a href="{{ route('support.faq') }}#return" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
              <div class="card-body text-center p-4">
                <i class="bi bi-arrow-repeat mb-3" style="font-size: 2.5rem; color: #fa709a;"></i>
                <h5 class="fw-bold">Đổi trả</h5>
                <p class="text-muted small mb-0">Chính sách hoàn trả</p>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="contact-info py-5 bg-light">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">Thông tin liên hệ</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
              <div class="icon-box" style="width: 50px; height: 50px; background: #667eea; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-telephone-fill text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
            <div>
              <h5 class="fw-bold mb-2">Hotline</h5>
              <p class="mb-1 text-primary fw-semibold" style="font-size: 1.2rem;">1900 1234</p>
              <p class="text-muted small mb-0">Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
              <div class="icon-box" style="width: 50px; height: 50px; background: #4facfe; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-envelope-fill text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
            <div>
              <h5 class="fw-bold mb-2">Email</h5>
              <p class="mb-1 text-primary fw-semibold">support@flowershop.com</p>
              <p class="text-muted small mb-0">Phản hồi trong 24 giờ</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
              <div class="icon-box" style="width: 50px; height: 50px; background: #f5576c; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-geo-alt-fill text-white" style="font-size: 1.5rem;"></i>
              </div>
            </div>
            <div>
              <h5 class="fw-bold mb-2">Địa chỉ</h5>
              <p class="mb-1">123 Đường Hoa, Quận 1</p>
              <p class="text-muted small mb-0">TP. Hồ Chí Minh, Việt Nam</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Top FAQs Preview -->
  <div class="top-faqs py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold mb-3">Câu hỏi thường gặp nhất</h2>
        <p class="text-muted">Những câu hỏi phổ biến từ khách hàng</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="accordion" id="topFaqAccordion">
            <div class="accordion-item border-0 shadow-sm mb-3">
              <h2 class="accordion-header">
                <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                  <i class="bi bi-question-circle-fill me-3 text-primary"></i>
                  Làm thế nào để đặt hàng?
                </button>
              </h2>
              <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#topFaqAccordion">
                <div class="accordion-body text-muted">
                  Để đặt hàng, bạn chỉ cần: (1) Chọn sản phẩm yêu thích và thêm vào giỏ hàng, (2) Tiến hành thanh toán, (3) Nhập thông tin giao hàng, (4) Chọn phương thức thanh toán (MoMo, VNPay hoặc COD), (5) Xác nhận đơn hàng. Bạn sẽ nhận được email xác nhận ngay sau khi đặt hàng thành công.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 shadow-sm mb-3">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                  <i class="bi bi-question-circle-fill me-3 text-primary"></i>
                  Các phương thức thanh toán nào được hỗ trợ?
                </button>
              </h2>
              <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#topFaqAccordion">
                <div class="accordion-body text-muted">
                  Chúng tôi hỗ trợ 3 phương thức thanh toán: (1) Ví điện tử MoMo (quét QR Code), (2) Cổng thanh toán VNPay (thẻ ATM/Visa/MasterCard), (3) Thanh toán khi nhận hàng (COD). Tất cả các phương thức đều được mã hóa và bảo mật tuyệt đối.
                </div>
              </div>
            </div>

            <div class="accordion-item border-0 shadow-sm mb-3">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                  <i class="bi bi-question-circle-fill me-3 text-primary"></i>
                  Thời gian giao hàng là bao lâu?
                </button>
              </h2>
              <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#topFaqAccordion">
                <div class="accordion-body text-muted">
                  Thời gian giao hàng tiêu chuẩn là 1-2 ngày làm việc trong nội thành. Đối với khu vực ngoại thành hoặc tỉnh thành khác, thời gian giao hàng có thể từ 2-5 ngày. Bạn sẽ nhận được thông báo cập nhật tình trạng đơn hàng qua SMS và email.
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <a href="{{ route('support.faq') }}" class="btn btn-outline-primary btn-lg">
              Xem tất cả câu hỏi <i class="bi bi-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Order Tracking Modal -->
<div class="modal fade" id="trackOrderModal" tabindex="-1" aria-labelledby="trackOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="trackOrderModalLabel">
          <i class="bi bi-truck me-2"></i>Tra cứu đơn hàng
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        @auth
        <div class="mb-4">
          <p class="text-muted">Xin chào <strong>{{ auth()->user()->name }}</strong>, bạn có thể xem tất cả đơn hàng của mình tại:</p>
          <a href="{{ route('orders.index') }}" class="btn btn-primary w-100 btn-lg">
            <i class="bi bi-list-check me-2"></i>Xem đơn hàng của tôi
          </a>
        </div>
        <div class="text-center my-3">
          <span class="text-muted">hoặc</span>
        </div>
        @endauth

        <form id="trackOrderForm">
          <div class="mb-3">
            <label for="orderNumber" class="form-label fw-semibold">Mã đơn hàng</label>
            <input type="text" class="form-control form-control-lg" id="orderNumber" placeholder="Nhập mã đơn hàng (VD: ORD20231126...)">
            <small class="text-muted">Mã đơn hàng được gửi qua email sau khi đặt hàng thành công</small>
          </div>

          <div class="mb-4">
            <label for="orderEmail" class="form-label fw-semibold">Email đặt hàng</label>
            <input type="email" class="form-control form-control-lg" id="orderEmail" placeholder="email@example.com" @auth value="{{ auth()->user()->email }}" @endauth>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-search me-2"></i>Tra cứu đơn hàng
          </button>
        </form>

        <div id="trackingResult" class="mt-4" style="display: none;">
          <!-- Tracking results will be displayed here -->
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.accordion-button:not(.collapsed) {
  background-color: #f8f9fa;
  color: #667eea;
}

.accordion-button:focus {
  box-shadow: none;
  border-color: rgba(102, 126, 234, 0.25);
}

.icon-circle, .icon-box {
  transition: transform 0.3s ease;
}

.hover-lift:hover .icon-circle,
.hover-lift:hover .icon-box {
  transform: scale(1.1);
}

#searchHelp:focus {
  box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
  border-color: #667eea;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Search functionality
  const searchInput = document.getElementById('searchHelp');
  searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      const query = this.value.trim();
      if (query) {
        window.location.href = '{{ route("support.faq") }}?search=' + encodeURIComponent(query);
      }
    }
  });

  // Order tracking form
  const trackForm = document.getElementById('trackOrderForm');
  if (trackForm) {
    trackForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const orderNumber = document.getElementById('orderNumber').value.trim();
      const orderEmail = document.getElementById('orderEmail').value.trim();
      
      if (!orderNumber || !orderEmail) {
        alert('Vui lòng nhập đầy đủ mã đơn hàng và email');
        return;
      }

      // In a real implementation, this would make an AJAX call to check order status
      // For now, redirect to orders page if logged in, or show a message
      @auth
        window.location.href = '{{ route("orders.index") }}';
      @else
        alert('Chức năng tra cứu đơn hàng cho khách vãng lai đang được phát triển. Vui lòng đăng nhập để xem đơn hàng của bạn.');
        window.location.href = '{{ route("login") }}';
      @endauth
    });
  }
});
</script>

@endsection
