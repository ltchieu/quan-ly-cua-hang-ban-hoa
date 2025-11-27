@extends('layouts.app')

@section('title', 'Mã giảm giá')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
      <li class="breadcrumb-item active">Mã giảm giá</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <h3 class="mb-4"><i class="bi bi-ticket-perforated me-2"></i> Mã giảm giá khả dụng</h3>

      @if($vouchers->count() > 0)
        <div class="row g-3">
          @foreach($vouchers as $voucher)
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 border-2" style="border-color: #ff7a00;">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title text-brand" style="color: #ff7a00;">
                      <i class="bi bi-ticket"></i> {{ $voucher->code }}
                    </h5>
                    <span class="badge bg-success">
                      @if($voucher->type === 'percent')
                        {{ $voucher->value }}%
                      @else
                        {{ number_format($voucher->value, 0, '.', ',') }} ₫
                      @endif
                    </span>
                  </div>

                  <p class="card-text text-muted">{{ $voucher->description }}</p>

                  <div class="mb-3">
                    @if($voucher->ends_at)
                    <small class="text-muted d-block">
                      <i class="bi bi-calendar"></i>
                      Hết hạn: {{ $voucher->ends_at->format('d/m/Y') }}
                    </small>
                    @else
                    <small class="text-muted d-block">
                      <i class="bi bi-infinity"></i> Không hết hạn
                    </small>
                    @endif
                    
                    @if($voucher->min_total)
                      <small class="text-muted d-block">
                        <i class="bi bi-currency-dollar"></i>
                        Đơn tối thiểu: {{ number_format($voucher->min_total, 0, '.', ',') }} ₫
                      </small>
                    @endif
                  </div>

                  <button class="btn btn-brand w-100 btn-sm" data-code="{{ $voucher->code }}" onclick="copyCode(this)">
                    <i class="bi bi-clipboard me-1"></i> Sao chép mã
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-info text-center">
          <i class="bi bi-ticket-perforated"></i>
          <p class="mb-0 mt-2">Hiện tại không có mã giảm giá khả dụng.</p>
        </div>
      @endif
    </div>
  </div>

  <script>
    function copyCode(btn) {
      const code = btn.getAttribute('data-code');
      navigator.clipboard.writeText(code).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i> Đã sao chép!';
        setTimeout(() => {
          btn.innerHTML = originalText;
        }, 2000);
      });
    }
  </script>
@endsection
