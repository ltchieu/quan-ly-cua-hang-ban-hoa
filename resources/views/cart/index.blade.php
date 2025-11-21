@extends('layouts.app')
@section('title','Giỏ hàng')

@section('content')
<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<h3>Giỏ hàng</h3>

@if(empty($cart))
  <p>Giỏ hàng trống. <a href="{{ route('products.index') }}">Mua sắm ngay</a></p>
@else
  <div class="row">
    <div class="col-lg-8">
      <div class="table-responsive bg-white border rounded">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Sản Phẩm</th>
            <th>Giá</th>
            <th style="width:150px">Số lượng</th>
            <th>Tạm tính</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($cart as $id => $i)
          <tr>
            <td class="d-flex align-items-center gap-2">
              <img src="{{ $i['image'] ? asset('storage/'.$i['image']) : 'https://placehold.co/60' }}"
                   style="width:60px;height:60px;object-fit:cover" class="rounded border">
              <div>
                <div class="fw-semibold">{{ $i['name'] }}</div>
              </div>
            </td>

            <td>{{ vnd($i['price']) }}</td>

            <td>
              <form action="{{ route('cart.update',$id) }}" method="post" class="d-flex">
                @csrf @method('PATCH')
                <input type="number" name="qty" min="1" value="{{ $i['qty'] }}" class="form-control" style="width:90px">
                <button class="btn btn-sm btn-outline-secondary ms-2">Cập nhật</button>
              </form>
            </td>

            <td>{{ vnd($i['price'] * $i['qty']) }}</td>

            <td class="text-end">
              <form action="{{ route('cart.remove',$id) }}" method="post">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0">Mã giảm giá</h5>
        </div>
        <div class="card-body">
          @if($appliedVoucher)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>✓ Đã áp dụng</strong><br>
              Mã: <strong>{{ $appliedVoucher['code'] }}</strong><br>
              Giảm: <strong class="text-danger">{{ vnd($appliedVoucher['discount']) }}</strong>
              <button type="button" class="btn-close" onclick="removeVoucher()"></button>
            </div>
          @else
            <div class="input-group mb-2">
              <input type="text" id="voucherCode" class="form-control" placeholder="Nhập mã giảm giá" required>
              <button class="btn btn-primary" onclick="applyVoucher()">Áp dụng</button>
            </div>
            <small class="text-muted">Nhập mã giảm giá của bạn</small>
          @endif
        </div>
      </div>

      <div class="card shadow-sm mt-3">
        <div class="card-header bg-light">
          <h5 class="mb-0">Tổng hóa đơn</h5>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span>Tạm tính:</span>
            <span>{{ vnd($total) }}</span>
          </div>
          @if($discount > 0)
            <div class="d-flex justify-content-between mb-2 text-danger">
              <span>Giảm giá:</span>
              <span>-{{ vnd($discount) }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Tổng cộng:</strong>
              <strong class="text-success" style="font-size: 1.2rem;">{{ vnd($finalTotal) }}</strong>
            </div>
          @else
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Tổng cộng:</strong>
              <strong style="font-size: 1.2rem; color: #ff7a00;">{{ vnd($total) }}</strong>
            </div>
          @endif
        </div>
      </div>

      <div class="text-end mt-3">
        @auth
          <a href="{{ route('checkout.index') }}" class="btn btn-brand w-100">Thanh toán</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-brand w-100">Đăng nhập để thanh toán</a>
        @endauth
      </div>
    </div>
  </div>
@endif

<script>
// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#28a745' : '#dc3545';
    const icon = type === 'success' ? '✓' : '✕';
    
    toast.innerHTML = `
        <div style="
            background: ${bgColor};
            color: white;
            padding: 16px 24px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            animation: slideIn 0.3s ease-out;
            font-size: 14px;
            font-weight: 500;
        " id="toast-${Date.now()}">
            <span style="font-size: 20px;">${icon}</span>
            <span>${message}</span>
        </div>
        <style>
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        </style>
    `;
    
    const container = document.getElementById('toastContainer');
    container.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        const toastEl = document.getElementById(`toast-${Date.now()}`);
        if (toastEl) {
            toastEl.parentElement.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toastEl.parentElement.remove(), 300);
        }
    }, 4000);
}

function applyVoucher() {
    const code = document.getElementById('voucherCode').value.trim();
    if (!code) {
        showToast('Vui lòng nhập mã giảm giá', 'error');
        return;
    }

    fetch('{{ route("cart.applyVoucher") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ voucher_code: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast('Lỗi: ' + err.message, 'error'));
}

function removeVoucher() {
    fetch('{{ route("cart.removeVoucher") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(err => showToast('Lỗi: ' + err.message, 'error'));
}
</script>
@endsection
