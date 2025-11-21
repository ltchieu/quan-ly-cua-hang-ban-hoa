@extends('layouts.admin')

@section('page-title', 'Edit Order Status')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5>Edit Order #{{ $order->id }}</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Order Status</label>
            <select name="status" class="form-select" required>
              <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
              <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
              <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
              <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
              <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Note (Optional)</label>
            <textarea name="note" class="form-control" rows="3">{{ $order->note }}</textarea>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
