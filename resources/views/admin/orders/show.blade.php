@extends('layouts.admin')

@section('page-title', 'Order Details')

@section('content')
<div class="mb-3">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Back to Orders
  </a>
  <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
    <i class="bi bi-pencil"></i> Edit Status
  </a>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5>Order #{{ $order->id }}</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
              <tr>
                <td>{{ $item->product->name ?? 'Product deleted' }}</td>
                <td>{{ number_format($item->price) }} ₫</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price * $item->quantity) }} ₫</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>{{ number_format($order->total) }} ₫</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header">Customer Information</div>
      <div class="card-body">
        <p><strong>Name:</strong> {{ $order->full_name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        @if($order->user)
          <p><strong>Email:</strong> {{ $order->user->email }}</p>
        @endif
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Order Information</div>
      <div class="card-body">
        <p><strong>Status:</strong> 
          <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
            {{ ucfirst($order->status) }}
          </span>
        </p>
        <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        @if($order->note)
          <p><strong>Note:</strong> {{ $order->note }}</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
