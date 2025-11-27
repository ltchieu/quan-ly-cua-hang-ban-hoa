@extends('layouts.admin')
@section('title','Chi tiết hỗ trợ')

@section('content')
<div class="mb-3">
  <a href="{{ route('admin.support.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
    <i class="bi bi-arrow-left"></i> Quay lại danh sách
  </a>
  <h4 class="mb-0">Ticket #{{ $ticket->ticket_number }}</h4>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header">
        <h5 class="mb-0">{{ $ticket->subject }}</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <strong>Nội dung:</strong>
          <p class="mt-2">{{ $ticket->message }}</p>
        </div>
        
        @if($ticket->admin_response)
          <div class="border-top pt-3">
            <strong>Phản hồi của Admin:</strong>
            <p class="mt-2 text-success">{{ $ticket->admin_response }}</p>
            <small class="text-muted">Phản hồi lúc: {{ $ticket->responded_at?->format('d/m/Y H:i') }}</small>
          </div>
        @endif
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Phản hồi cho khách hàng</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.support.respond', $ticket->id) }}" method="POST">
          @csrf
          
          <div class="mb-3">
            <label for="admin_response" class="form-label">Nội dung phản hồi</label>
            <textarea class="form-control @error('admin_response') is-invalid @enderror" 
                      id="admin_response" name="admin_response" rows="5" required>{{ old('admin_response', $ticket->admin_response) }}</textarea>
            @error('admin_response')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
              <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Mới</option>
              <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>Đang xử lý</option>
              <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>Đã giải quyết</option>
              <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Đã đóng</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header">
        <h6 class="mb-0">Thông tin ticket</h6>
      </div>
      <div class="card-body">
        <table class="table table-sm mb-0">
          <tr>
            <td><strong>Mã ticket:</strong></td>
            <td>{{ $ticket->ticket_number }}</td>
          </tr>
          <tr>
            <td><strong>Danh mục:</strong></td>
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
              {{ $categoryLabels[$ticket->category] ?? $ticket->category }}
            </td>
          </tr>
          <tr>
            <td><strong>Trạng thái:</strong></td>
            <td>
              @php
                $statusLabels = [
                  'open' => 'Mới',
                  'in_progress' => 'Đang xử lý',
                  'resolved' => 'Đã giải quyết',
                  'closed' => 'Đã đóng'
                ];
              @endphp
              <span class="badge 
                {{ $ticket->status == 'open' ? 'bg-primary' : '' }}
                {{ $ticket->status == 'in_progress' ? 'bg-warning' : '' }}
                {{ $ticket->status == 'resolved' ? 'bg-success' : '' }}
                {{ $ticket->status == 'closed' ? 'bg-secondary' : '' }}
              ">
                {{ $statusLabels[$ticket->status] ?? $ticket->status }}
              </span>
            </td>
          </tr>
          <tr>
            <td><strong>Ngày tạo:</strong></td>
            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Thông tin khách hàng</h6>
      </div>
      <div class="card-body">
        <p class="mb-1"><strong>Tên:</strong> {{ $ticket->user->name ?? 'N/A' }}</p>
        <p class="mb-1"><strong>Email:</strong> {{ $ticket->user->email ?? 'N/A' }}</p>
        <p class="mb-0"><strong>Điện thoại:</strong> {{ $ticket->user->phone ?? 'N/A' }}</p>
      </div>
    </div>

    <div class="mt-3">
      <form action="{{ route('admin.support.destroy', $ticket->id) }}" method="POST" 
            onsubmit="return confirm('Bạn có chắc muốn xóa ticket này?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm w-100">
          <i class="bi bi-trash"></i> Xóa ticket
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
