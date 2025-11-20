@extends('layouts.app')

@section('title', 'Thanh toán Momo')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-lg">
        <div class="card-header" style="background: linear-gradient(135deg, #a80030 0%, #d63031 100%); color: white;">
          <h4 class="mb-0 text-center">
            <i class="bi bi-wallet2 me-2"></i>Thanh toán bằng Momo
          </h4>
        </div>

        <div class="card-body text-center py-5">
          <!-- QR Code Display -->
          <div class="mb-4">
            <p class="text-muted mb-3">Quét mã QR để thanh toán đơn hàng</p>
            <div
              style="background: white; padding: 20px; border-radius: 8px; display: inline-block; border: 2px solid #f0f0f0;">
              <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

              <canvas id="qr-code"></canvas>

              <script>
                (function () {
                  var qr = new QRious({
                    element: document.getElementById('qr-code'),
                    value: '{{ $payUrl }}', // Dữ liệu là đường dẫn thanh toán MoMo
                    size: 300
                  });
                })();
              </script>
            </div>
          </div>

          <!-- Order Info -->
          <div class="alert alert-info mb-4">
            <h6 class="mb-2">Thông tin thanh toán</h6>
            <p class="mb-1"><strong>Mã giao dịch:</strong> {{ $tempOrderId }}</p>
            <p class="mb-0"><strong>Số tiền:</strong> <span
                style="color: #a80030; font-weight: bold; font-size: 1.1rem;">{{ number_format($total, 0, '.', ',') }}
                ₫</span></p>
          </div>

          <!-- Instructions -->
          <div class="text-start mb-4">
            <h6 class="mb-2">Hướng dẫn:</h6>
            <ol class="small text-muted">
              <li>Mở ứng dụng Momo trên điện thoại</li>
              <li>Chọn "Quét mã QR" hoặc biểu tượng camera</li>
              <li>Quét mã QR bên trên</li>
              <li>Xác nhận và hoàn tất thanh toán</li>
            </ol>
          </div>

          <!-- Fallback Link -->
          <div class="mb-4 pb-3 border-bottom">
            <p class="text-muted small mb-2">Nếu không thể quét QR, bạn có thể:</p>
            <a href="{{ $payUrl }}" target="_blank" class="btn btn-danger btn-sm">
              <i class="bi bi-box-arrow-up-right me-1"></i>Mở trang thanh toán Momo
            </a>
          </div>

          <!-- Status Check -->
          <div class="mb-4">
            <p class="text-muted small">Hệ thống sẽ tự động chuyển hướng khi thanh toán hoàn tất.</p>
            <div class="spinner-border spinner-border-sm text-secondary" role="status">
              <span class="visually-hidden">Đang chờ...</span>
            </div>
            <span class="text-muted small ms-2">Đang chờ thanh toán...</span>
          </div>

          <!-- Important Note -->
          <div class="alert alert-warning small mb-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Lưu ý:</strong> Nếu bạn muốn quay lại, hãy sử dụng nút bên dưới. Giỏ hàng của bạn sẽ được lưu lại.
          </div>

          <!-- Cancel Button - More Prominent -->
          <a href="{{ route('payment.cancel', ['tempOrderId' => $tempOrderId]) }}" class="btn btn-warning w-100">
            <i class="bi bi-arrow-left me-2"></i>Hủy và quay lại để chọn phương thức khác
          </a>
          <p class="text-muted small mt-3 mb-0">💡 Sử dụng nút trên để đảm bảo giỏ hàng được lưu</p>
        </div>

        <div class="card-footer bg-light text-center text-muted small">
          <i class="bi bi-shield-check me-1"></i>Giao dịch được bảo mật bởi Momo
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const paymentCheckInterval = setInterval(function () {
        fetch('{{ route("payment.status", ["tempOrderId" => $tempOrderId]) }}')
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              clearInterval(paymentCheckInterval);
              window.location.href = data.redirect_url;
            }
          })
          .catch(err => console.log('Checking payment status...'));
      }, 5000); // Check every 5 seconds
    });
  </script>
@endsection