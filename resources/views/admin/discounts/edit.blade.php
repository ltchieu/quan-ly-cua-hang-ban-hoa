@extends('layouts.admin')
@section('title','Chỉnh sửa mã giảm giá')

@section('content')
<div class="mb-3">
  <h4 class="mb-0">Chỉnh sửa mã giảm giá: {{ $discount->code }}</h4>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('code') is-invalid @enderror" 
                 id="code" name="code" value="{{ old('code', $discount->code) }}" 
                 placeholder="VD: SUMMER2024" required>
          @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="description" class="form-label">Mô tả</label>
          <input type="text" class="form-control @error('description') is-invalid @enderror" 
                 id="description" name="description" value="{{ old('description', $discount->description) }}" 
                 placeholder="Giảm giá mùa hè">
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
            <option value="percent" {{ old('type', $discount->type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
            <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
          </select>
          @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="value" class="form-label">Giá trị <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('value') is-invalid @enderror" 
                 id="value" name="value" value="{{ old('value', $discount->value) }}" 
                 min="0" required>
          @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="min_total" class="form-label">Đơn hàng tối thiểu (VNĐ) <span class="text-danger">*</span></label>
          <input type="number" class="form-control @error('min_total') is-invalid @enderror" 
                 id="min_total" name="min_total" value="{{ old('min_total', $discount->min_total) }}" 
                 min="0" required>
          @error('min_total')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="starts_at" class="form-label">Ngày bắt đầu</label>
          <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                 id="starts_at" name="starts_at" 
                 value="{{ old('starts_at', $discount->starts_at ? $discount->starts_at->format('Y-m-d\TH:i') : '') }}">
          @error('starts_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="ends_at" class="form-label">Ngày kết thúc</label>
          <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" 
                 id="ends_at" name="ends_at" 
                 value="{{ old('ends_at', $discount->ends_at ? $discount->ends_at->format('Y-m-d\TH:i') : '') }}">
          @error('ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="usage_limit" class="form-label">Giới hạn sử dụng</label>
          <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                 id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $discount->usage_limit) }}" 
                 min="1" placeholder="Không giới hạn">
          @error('usage_limit')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="text-muted">Đã sử dụng: {{ $discount->used }} lần</small>
        </div>
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                 {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">
            Đang hoạt động
          </label>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</div>
@endsection
