@extends('layouts.admin')
@section('page-title', 'News Management')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">News Articles</h5>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add News</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead><tr><th>ID</th><th>Title</th><th>Published</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($news as $article)
          <tr>
            <td>{{ $article->id }}</td>
            <td>{{ $article->title }}</td>
            <td><span class="badge bg-{{ $article->is_published ? 'success' : 'secondary' }}">{{ $article->is_published ? 'Published' : 'Draft' }}</span></td>
            <td>{{ $article->created_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
              <form action="{{ route('admin.news.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted py-4">No news found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $news->links() }}</div>
  </div>
</div>
@endsection
