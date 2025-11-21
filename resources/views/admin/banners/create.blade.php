@extends('layouts.admin')
@section('page-title', 'Create Banner')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h5>Create Banner</h5></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Image *</label>
            <input type="file" name="image" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Link (URL)</label>
            <input type="url" name="link" class="form-control" value="{{ old('link') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', 0) }}" min="0">
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" checked>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Banner</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
