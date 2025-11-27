@extends('layouts.app')
@section('title',$product->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row g-4">
  <div class="col-md-5">
    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/800x800?text=Flower' }}" class="img-fluid rounded shadow-sm">
  </div>
  <div class="col-md-7">
    <h3 class="mb-1">{{ $product->name }}</h3>
    <div class="mb-2 text-muted">Danh mục: <strong>{{ $product->category->name ?? '' }}</strong></div>
    <div class="d-flex align-items-center gap-2 mb-2">
      <x-stars :value="$avg" />
      <small class="text-muted">({{ number_format($product->reviews()->count()) }} đánh giá)</small>
    </div>
    <div class="fs-3 fw-bold text-danger">
      {{ vnd($product->sale_price ?? $product->price) }}
      @if($product->sale_price)<span class="old-price fs-6">{{ vnd($product->price) }}</span>@endif
    </div>
    <p class="mt-3">{{ $product->description }}</p>

    <div class="d-flex gap-2">
      <form action="{{ route('cart.add',$product) }}" method="post" class="d-flex gap-2">
        @csrf
        <input type="number" name="qty" value="1" min="1" class="form-control" style="width:120px" id="qtyInput">
        <button class="btn btn-brand">Thêm giỏ hàng</button>
      </form>
      
      <form action="{{ route('cart.buyNow',$product) }}" method="post" class="d-flex gap-2" onsubmit="document.getElementById('buyNowQty').value = document.getElementById('qtyInput').value">
        @csrf
        <input type="hidden" name="qty" value="1" id="buyNowQty">
        <button class="btn btn-danger">Mua ngay</button>
      </form>
    </div>
  </div>
</div>

<hr class="my-4">
<h5 class="mb-3">Đánh giá</h5>
@auth
<form action="{{ route('reviews.store',$product) }}" method="post" class="row g-2 mb-3">
  @csrf
  <div class="col-md-2">
    <select name="rating" class="form-select">
      @for($i=5;$i>=1;$i--) <option value="{{ $i }}">{{ $i }} ⭐</option> @endfor
    </select>
  </div>
  <div class="col-md-8"><input name="content" class="form-control" placeholder="Cảm nhận của bạn..."></div>
  <div class="col-md-2"><button class="btn btn-brand w-100">Gửi</button></div>
</form>
@endauth

@forelse($product->reviews()->latest()->take(10)->get() as $r)
  <div class="mb-3 p-3 bg-white border rounded">
    <div class="d-flex align-items-center gap-2 mb-1">
      <strong>{{ $r->user->name ?? 'Ẩn danh' }}</strong>
      <x-stars :value="$r->rating" :size="14" />
    </div>
    <div>{{ $r->content }}</div>
  </div>
@empty
  <p>Chưa có đánh giá.</p>
@endforelse

<hr class="my-4">
<h5 class="mb-3">Sản phẩm liên quan</h5>
@php
  $related = \App\Models\Product::where('category_id',$product->category_id)
            ->where('id','<>',$product->id)->latest()->take(4)->get();
@endphp
<div class="row g-3">
@foreach($related as $p)
  <div class="col-6 col-md-3">
    <div class="card card-product h-100 shadow-sm">
      <img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/600x600?text=Flower' }}" class="thumb" alt="">
      <div class="card-body d-flex flex-column">
        <h6 class="card-title mb-1">{{ $p->name }}</h6>
        <div class="mt-auto price">{{ vnd($p->sale_price ?? $p->price) }}</div>
        <a href="{{ route('products.show',$p) }}" class="btn btn-sm btn-brand mt-2">Xem</a>
      </div>
    </div>
  </div>
@endforeach
</div>
@endsection
