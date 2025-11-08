@extends('layouts.app')
@section('title','Giỏ hàng')

@section('content')
<h3>Giỏ hàng</h3>

@if(empty($cart))
  <p>Giỏ hàng trống. <a href="{{ route('products.index') }}">Mua sắm ngay</a></p>
@else
  <div class="table-responsive bg-white border rounded">
  <table class="table align-middle mb-0">
    <thead>
      <tr>
        <th>Sản Phẩm</th>
        <th>Giá</th>
        <th style="width:150px">Số lượng</th>
        <th>Tạm tính</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    @foreach($cart as $id => $i)
      <tr>
        <td class="d-flex align-items-center gap-2">
          <img src="{{ $i['image'] ? asset('storage/'.$i['image']) : 'https://placehold.co/60' }}"
               style="width:60px;height:60px;object-fit:cover" class="rounded border">
          <div>
            <div class="fw-semibold">{{ $i['name'] }}</div>
          </div>
        </td>

        <td>{{ vnd($i['price']) }}</td>

        <td>
          <form action="{{ route('cart.update',$id) }}" method="post" class="d-flex">
            @csrf @method('PATCH')
            <input type="number" name="qty" min="1" value="{{ $i['qty'] }}" class="form-control" style="width:90px">
            <button class="btn btn-sm btn-outline-secondary ms-2">Cập nhật</button>
          </form>
        </td>

        <td>{{ vnd($i['price'] * $i['qty']) }}</td>

        <td class="text-end">
          <form action="{{ route('cart.remove',$id) }}" method="post">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Xóa</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  </div>

  <div class="text-end fs-5 mt-3">
    Tổng: <strong>{{ vnd($total) }}</strong>
  </div>

  <div class="text-end mt-3">
    @auth
      <a href="{{ route('checkout.index') }}" class="btn btn-brand">Thanh toán</a>
    @else
      <a href="{{ route('login') }}" class="btn btn-brand">Đăng nhập để thanh toán</a>
    @endauth
  </div>
@endif
@endsection
