@extends('layouts.admin')

@section('page-title', 'Income Reports')

@section('content')
<div class="card mb-4">
  <div class="card-header">
    <h5>Filter Reports</h5>
  </div>
  <div class="card-body">
    <form method="GET" class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">&nbsp;</label><br>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-filter"></i> Apply Filter
        </button>
      </div>
    </form>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card stats-card">
      <div class="card-body">
        <h6 class="text-muted">Total Revenue</h6>
        <h3 class="mb-0">{{ number_format($totalRevenue) }} ₫</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stats-card">
      <div class="card-body">
        <h6 class="text-muted">Total Orders</h6>
        <h3 class="mb-0">{{ $totalOrders }}</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stats-card">
      <div class="card-body">
        <h6 class="text-muted">Completed Orders</h6>
        <h3 class="mb-0">{{ $completedOrders }}</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">
        <h5>Revenue by Category</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Category</th>
                <th>Revenue</th>
                <th>Orders</th>
                <th>Items Sold</th>
              </tr>
            </thead>
            <tbody>
              @forelse($revenueByCategory as $category)
              <tr>
                <td>{{ $category->category_name }}</td>
                <td>{{ number_format($category->revenue) }} ₫</td>
                <td>{{ $category->order_count }}</td>
                <td>{{ $category->items_sold }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted">No data available</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">
        <h5>Top 10 Products</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topProducts as $product)
              <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->quantity_sold }}</td>
                <td>{{ number_format($product->revenue) }} ₫</td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="text-center text-muted">No data available</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h5>Daily Revenue</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-striped">
        <thead>
          <tr>
            <th>Date</th>
            <th>Orders</th>
            <th>Revenue</th>
          </tr>
        </thead>
        <tbody>
          @forelse($dailyRevenue as $day)
          <tr>
            <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
            <td>{{ $day->orders }}</td>
            <td>{{ number_format($day->revenue) }} ₫</td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="text-center text-muted">No data available</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
