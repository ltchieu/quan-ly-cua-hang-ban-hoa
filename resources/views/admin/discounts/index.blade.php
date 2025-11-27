@extends('layouts.admin')
@section('title','Quản lý mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Quản lý mã giảm giá</h4>
  <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">+ Thêm mã giảm giá</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Tìm theo mã...">
  </div>
  <div class="col-md-2">
    <select class="form-select" name="type">
      <option value="">-- Loại --</option>
      <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
      <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
    </select>
  </div>
  <div class="col-md-2">
    <select class="form-select" name="status">
      <option value="">-- Trạng thái --</option>
      <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
      <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
      <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">Lọc</button>
  </div>
</form>

<div class="table-responsive bg-white border rounded">
<table class="table align-middle mb-0">
  <thead>
    <tr>
      <th>Mã</th>
      <th>Mô tả</th>
      <th>Loại</th>
      <th>Giá trị</th>
      <th>Đơn tối thiểu</th>
      <th>Sử dụng</th>
      <th>Thời gian</th>
      <th>Trạng thái</th>
      <th style="width:160px"></th>
    </tr>
  </thead>
  <tbody>
  @forelse($vouchers as $v)
    <tr>
      <td><strong>{{ $v->code }}</strong></td>
      <td>{{ Str::limit($v->description, 30) ?? '-' }}</td>
      <td>
        @if($v->type == 'percent')
          <span class="badge bg-info">Phần trăm</span>
        @else
          <span class="badge bg-warning">Cố định</span>
        @endif
      </td>
      <td>
        @if($v->type == 'percent')
          {{ $v->value }}%
        @else
          {{ number_format($v->value) }}đ
        @endif
      </td>
      <td>{{ number_format($v->min_total) }}đ</td>
      <td>{{ $v->used }}{{ $v->usage_limit ? '/' . $v->usage_limit : '' }}</td>
      <td class="small">
        @if($v->starts_at)
          {{ $v->starts_at->format('d/m/Y') }}
        @else
          -
        @endif
        <br>
        @if($v->ends_at)
          {{ $v->ends_at->format('d/m/Y') }}
          @if($v->ends_at < now())
            <span class="badge bg-danger">Hết hạn</span>
          @endif
        @else
          Không giới hạn
        @endif
      </td>
      <td>
        @if($v->is_active && (!$v->ends_at || $v->ends_at > now()))
          <span class="badge bg-success">Hoạt động</span>
        @else
          <span class="badge bg-secondary">Không hoạt động</span>
        @endif
      </td>
      <td class="text-end">
        <a href="{{ route('admin.discounts.edit', $v->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
        <form action="{{ route('admin.discounts.destroy', $v->id) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa mã giảm giá này?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Xóa</button>
        </form>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="9" class="text-center py-4 text-muted">Chưa có mã giảm giá nào</td>
    </tr>
  @endforelse
  </tbody>
</table>
</div>

<div class="mt-3">{{ $vouchers->links('pagination::bootstrap-5') }}</div>
@endsection
