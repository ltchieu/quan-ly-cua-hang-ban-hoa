@extends('layouts.admin')
@section('page-title', 'Banners')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Banners</h5>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Banner</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>ID</th><th>Title</th><th>Image</th><th>Position</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($banners as $banner)
          <tr>
            <td>{{ $banner->id }}</td>
            <td>{{ $banner->title }}</td>
            <td><img src="{{ asset('storage/'.$banner->image) }}" height="50"></td>
            <td>{{ $banner->position }}</td>
            <td><span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">{{ $banner->is_active ? 'Active' : 'Inactive' }}</span></td>
            <td>
              <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-4">No banners found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $banners->links() }}</div>
  </div>
</div>
@endsection
