@extends('layouts.admin')

@section('page-title', 'Staff Management')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Staff Members</h5>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add Staff
    </a>
  </div>
  <div class="card-body">
    <form method="GET" class="mb-4">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
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
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staff as $member)
          <tr>
            <td>{{ $member->id }}</td>
            <td>{{ $member->name }}</td>
            <td>{{ $member->email }}</td>
            <td>{{ $member->phone ?? 'N/A' }}</td>
            <td>{{ $member->created_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              @if($member->id !== auth()->id())
              <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">No staff members found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $staff->links() }}
    </div>
  </div>
</div>
@endsection
