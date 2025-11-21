@extends('layouts.admin')

@section('page-title', 'Edit Staff')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><h5>Edit Staff: {{ $staff->name }}</h5></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.staff.update', $staff) }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $staff->address) }}</textarea>
          </div>
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Staff</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
