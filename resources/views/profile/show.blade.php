@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
      <li class="breadcrumb-item active">Hồ sơ</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body text-center">
          <i class="bi bi-person-circle" style="font-size: 4rem; color: #ff7a00;"></i>
          <h5 class="mt-3">{{ $user->name }}</h5>
          <p class="text-muted">{{ $user->email }}</p>
          <a href="{{ route('profile.edit') }}" class="btn btn-brand btn-sm w-100">Chỉnh sửa</a>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="card">
        <div class="card-header bg-light">
          <h5 class="mb-0">Thông tin cá nhân</h5>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <td class="fw-bold">Tên:</td>
              <td>{{ $user->name }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Email:</td>
              <td>{{ $user->email }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Số điện thoại:</td>
              <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Địa chỉ:</td>
              <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
              <td class="fw-bold">Ngày tạo tài khoản:</td>
              <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
          </table>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-header bg-light">
          <h5 class="mb-0">Liên kết nhanh</h5>
        </div>
        <div class="card-body">
          <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2"><i class="bi bi-clock-history me-2"></i> Lịch sử đơn hàng</a>
          <a href="{{ route('vouchers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-ticket-perforated me-2"></i> Mã giảm giá</a>
        </div>
      </div>
    </div>
  </div>
@endsection
