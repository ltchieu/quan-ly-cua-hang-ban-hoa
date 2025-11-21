@extends('layouts.admin')
@section('page-title', 'Edit News')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-10">
    <div class="card">
      <div class="card-header"><h5>Edit News Article</h5></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Summary</label>
            <textarea name="summary" class="form-control" rows="2">{{ old('summary', $news->summary) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Content *</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content', $news->content) }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Featured Image</label><br>
            @if($news->image)
              <img src="{{ asset('storage/'.$news->image) }}" height="100" class="mb-2"><br>
            @endif
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave empty to keep current image</small>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Published Date</label>
              <input type="datetime-local" name="published_at" class="form-control" 
                value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Status</label><br>
              <div class="form-check form-check-inline">
                <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Published</label>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update News</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
