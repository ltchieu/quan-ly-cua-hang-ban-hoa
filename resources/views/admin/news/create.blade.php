@extends('layouts.admin')
@section('page-title', 'Create News')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-10">
    <div class="card">
      <div class="card-header"><h5>Create News Article</h5></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Summary</label>
            <textarea name="summary" class="form-control" rows="2">{{ old('summary') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Content *</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Featured Image</label>
            <input type="file" name="image" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Published Date</label>
              <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status</label><br>
              <div class="form-check form-check-inline">
                <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" checked>
                <label class="form-check-label" for="is_published">Published</label>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create News</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
