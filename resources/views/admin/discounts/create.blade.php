@extends('layouts.admin')
@section('title','Tạo mã giảm giá mới')

@section('content')
<div class="mb-3">
  <h4 class="mb-0">Tạo mã giảm giá mới</h4>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.discounts.store') }}" method="POST">
      @csrf
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('code') is-invalid @enderror" 
                 id="code" name="code" value="{{ old('code') }}" 
                 placeholder="VD: SUMMER2024" required>
          @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Mã sẽ được tự động chuyển thành chữ in hoa</small>
        </div>

        <div class="col-md-6 mb-3">
          <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('description') is-invalid @enderror" 
                 id="description" name="description" value="{{ old('description') }}" 
                 placeholder="Giảm giá mùa hè" required>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
          <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="">-- Chọn loại --</option>
            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
          </select>
          @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="value" class="form-label">Giá trị <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('value') is-invalid @enderror" 
                 id="value" name="value" value="{{ old('value') }}" 
                 min="0" required>
          @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Nhập số (VD: 10 cho 10% hoặc 50000 cho 50,000đ)</small>
        </div>

        <div class="col-md-4 mb-3">
          <label for="min_total" class="form-label">Đơn hàng tối thiểu (VNĐ) <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('min_total') is-invalid @enderror" 
                 id="min_total" name="min_total" value="{{ old('min_total', 0) }}" 
                 min="0" required>
          @error('min_total')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="starts_at" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
          <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                 id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
          @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="ends_at" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
          <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" 
                 id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required>
          @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="usage_limit" class="form-label">Giới hạn sử dụng <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                 id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" 
                 min="1" placeholder="Không giới hạn" required>
          @error('usage_limit')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                 {{ old('is_active', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">
            Kích hoạt ngay
          </label>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Tạo mã giảm giá</button>
        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const startsAt = document.getElementById('starts_at');
    const endsAt = document.getElementById('ends_at');

    // Set min date to current time
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    startsAt.min = now.toISOString().slice(0, 16);

    startsAt.addEventListener('change', function() {
      endsAt.min = this.value;
    });
    
    endsAt.addEventListener('change', function() {
        if(startsAt.value && this.value < startsAt.value){
            alert('Ngày kết thúc phải sau ngày bắt đầu');
            this.value = '';
        }
    });
  });
</script>
@endsection
