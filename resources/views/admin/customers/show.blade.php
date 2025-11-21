@extends('layouts.admin')

@section('page-title', 'Customer Details')

@section('content')
<div class="mb-3">
  <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><h5>Customer Information</h5></div>
      <div class="card-body">
        <p><strong>Name:</strong> {{ $customer->name }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
        <p><strong>Joined:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header"><h5>Statistics</h5></div>
      <div class="card-body">
        <p><strong>Total Orders:</strong> {{ $stats['total_orders'] }}</p>
        <p><strong>Total Spent:</strong> {{ number_format($stats['total_spent']) }} ₫</p>
        <p><strong>Pending Orders:</strong> {{ $stats['pending_orders'] }}</p>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h5>Order History</h5></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($customer->orders as $order)
              <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ number_format($order->total) }} ₫</td>
                <td><span class="badge bg-info">{{ $order->status }}</span></td>
                <td>
                  <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-3">No orders yet</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
