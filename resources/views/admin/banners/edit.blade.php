@extends('layouts.admin')
@section('page-title', 'Edit Banner')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h5>Edit Banner</h5></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $banner->description) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="{{ asset('storage/'.$banner->image) }}" height="100" class="mb-2">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave empty to keep current image</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Link (URL)</label>
            <input type="url" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', $banner->position) }}" min="0">
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Banner</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
