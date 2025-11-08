@extends('layouts.app')
@section('title','Thanh toán')
@section('content')
<h3>Thanh toán</h3>
<form action="{{ route('checkout.store') }}" method="post" class="row g-3">
  @csrf
  <div class="col-md-6">
    <label class="form-label">Họ tên</label>
    <input name="full_name" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">SĐT</label>
    <input name="phone" class="form-control" required>
  </div>
  <div class="col-md-9">
    <label class="form-label">Địa chỉ</label>
    <input name="address" class="form-control" required>
  </div>
  <div class="col-md-12">
    <label class="form-label">Ghi chú</label>
    <input name="note" class="form-control">
  </div>
  <input type="hidden" name="payment_method" value="COD">
  <div class="col-12 text-end">
    <button class="btn btn-brand">Đặt hàng</button>
  </div>
</form>
@endsection
