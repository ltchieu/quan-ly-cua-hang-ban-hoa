@extends('layouts.admin')
@section('title','Quản lý hỗ trợ khách hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Quản lý hỗ trợ khách hàng</h4>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Tìm theo mã ticket, chủ đề...">
  </div>
  <div class="col-md-2">
    <select class="form-select" name="status">
      <option value="">-- Trạng thái --</option>
      <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Mới</option>
      <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang xử lý</option>
      <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã giải quyết</option>
      <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
    </select>
  </div>
  <div class="col-md-2">
    <select class="form-select" name="category">
      <option value="">-- Danh mục --</option>
      <option value="order" {{ request('category') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
      <option value="product" {{ request('category') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
      <option value="payment" {{ request('category') == 'payment' ? 'selected' : '' }}>Thanh toán</option>
      <option value="shipping" {{ request('category') == 'shipping' ? 'selected' : '' }}>Vận chuyển</option>
      <option value="return" {{ request('category') == 'return' ? 'selected' : '' }}>Đổi/Trả hàng</option>
      <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Khác</option>
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
      <th>Mã ticket</th>
      <th>Khách hàng</th>
      <th>Chủ đề</th>
      <th>Danh mục</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th style="width:120px"></th>
    </tr>
  </thead>
  <tbody>
  @forelse($tickets as $ticket)
    <tr>
      <td><strong>{{ $ticket->ticket_number }}</strong></td>
      <td>{{ $ticket->user->name ?? 'N/A' }}<br><small class="text-muted">{{ $ticket->user->email ?? '' }}</small></td>
      <td>{{ Str::limit($ticket->subject, 40) }}</td>
      <td>
        @php
          $categoryLabels = [
            'order' => 'Đơn hàng',
            'product' => 'Sản phẩm',
            'payment' => 'Thanh toán',
            'shipping' => 'Vận chuyển',
            'return' => 'Đổi/Trả hàng',
            'other' => 'Khác'
          ];
        @endphp
        <span class="badge bg-secondary">{{ $categoryLabels[$ticket->category] ?? $ticket->category }}</span>
      </td>
      <td>
        @php
          $statusBadges = [
            'open' => 'bg-primary',
            'in_progress' => 'bg-warning',
            'resolved' => 'bg-success',
            'closed' => 'bg-secondary'
          ];
          $statusLabels = [
            'open' => 'Mới',
            'in_progress' => 'Đang xử lý',
            'resolved' => 'Đã giải quyết',
            'closed' => 'Đã đóng'
          ];
        @endphp
        <span class="badge {{ $statusBadges[$ticket->status] ?? 'bg-secondary' }}">
          {{ $statusLabels[$ticket->status] ?? $ticket->status }}
        </span>
      </td>
      <td class="small">
        {{ $ticket->created_at->format('d/m/Y H:i') }}
        <br>
        <span class="text-muted">{{ $ticket->created_at->diffForHumans() }}</span>
      </td>
      <td class="text-end">
        <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="7" class="text-center py-4 text-muted">Chưa có ticket nào</td>
    </tr>
  @endforelse
  </tbody>
</table>
</div>

<div class="mt-3">{{ $tickets->links('pagination::bootstrap-5') }}</div>
@endsection
