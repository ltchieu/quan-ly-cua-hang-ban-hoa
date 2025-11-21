@extends('layouts.admin')

@section('page-title', 'Customers')

@section('content')
<div class="card">
  <div class="card-header"><h5>All Customers</h5></div>
  <div class="card-body">
    <form method="GET" class="mb-4">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control" placeholder="Search customers" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Orders</th>
            <th>Joined</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $customer)
          <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone ?? 'N/A' }}</td>
            <td>{{ $customer->orders_count }}</td>
            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">
                <i class="bi bi-eye"></i>
              </a>
              <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No customers found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $customers->links() }}
    </div>
  </div>
</div>
@endsection
