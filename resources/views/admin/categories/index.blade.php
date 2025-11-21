@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Product Categories</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Add Category
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Products</th>
            <th>Position</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->products_count }} products</td>
            <td>{{ $category->position }}</td>
            <td>
              <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
            <td colspan="6" class="text-center text-muted py-4">No categories found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
