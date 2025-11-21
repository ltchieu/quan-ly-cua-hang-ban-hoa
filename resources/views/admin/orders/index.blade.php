@extends('layouts.admin')

@section('page-title', 'Orders Management')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">All Orders</h5>
  </div>
  <div class="card-body">
    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Search by ID, name, phone" value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All Status</option>
          <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
          <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
          <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
          <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
          <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Filter
        </button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
          <tr>
            <td>#{{ $order->id }}</td>
            <td>
              {{ $order->full_name }}
              @if($order->user)
                <br><small class="text-muted">{{ $order->user->email }}</small>
              @endif
            </td>
            <td>{{ $order->phone }}</td>
            <td>{{ number_format($order->total) }} ₫</td>
            <td>
              @if($order->payment_method == 'COD')
                <span class="badge bg-secondary">COD</span>
              @else
                <span class="badge bg-info">{{ strtoupper($order->payment_method) }}</span>
              @endif
            </td>
            <td>
              @php
                $statusColors = [
                  'pending' => 'warning',
                  'confirmed' => 'info',
                  'processing' => 'primary',
                  'shipped' => 'success',
                  'delivered' => 'success',
                  'cancelled' => 'danger'
                ];
                $color = $statusColors[$order->status] ?? 'secondary';
              @endphp
              <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
            </td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info" title="View">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No orders found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $orders->links() }}
    </div>
  </div>
</div>
@endsection
