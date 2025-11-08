@extends('layouts.app')
@section('title','Sản phẩm')
@section('content')
<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input name="q" value="{{ $q }}" class="form-control" placeholder="Tìm hoa...">
  </div>
  <div class="col-md-3">
    <select name="category" class="form-select">
      <option value="">-- Tất cả danh mục --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected($cat==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2"><input name="pmin" value="{{ $pmin }}" class="form-control" placeholder="Giá từ"></div>
  <div class="col-md-2"><input name="pmax" value="{{ $pmax }}" class="form-control" placeholder="đến"></div>
  <div class="col-md-2">
    <select name="sort" class="form-select">
      <option value="new" @selected($sort==='new')>Mới nhất</option>
      <option value="price_asc" @selected($sort==='price_asc')>Giá tăng</option>
      <option value="price_desc" @selected($sort==='price_desc')>Giá giảm</option>
    </select>
  </div>
  <div class="col-12"><button class="btn btn-brand">Lọc</button></div>
</form>

@if($products->isEmpty())
  <div class="alert alert-warning">Chưa có sản phẩm phù hợp.</div>
@else
<div class="row g-3">
@foreach($products as $p)
  <div class="col-6 col-md-3">
    <div class="card card-product h-100 shadow-sm">
      <img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/600x600?text=Flower' }}" class="thumb" alt="">
      <div class="card-body d-flex flex-column">
        <h6 class="card-title mb-1">{{ $p->name }}</h6>
        <small class="text-muted">{{ $p->category->name ?? '' }}</small>
        <div class="mt-auto">
        <div class="price">
          {{ vnd($p->sale_price ?? $p->price) }}
          @if($p->sale_price)<span class="old-price">{{ vnd($p->price) }}</span>@endif
        </div>
          <a href="{{ route('products.show',$p) }}" class="btn btn-sm btn-brand mt-2 w-100">Xem</a>
        </div>
      </div>
    </div>
  </div>
@endforeach
</div>

<div class="mt-3">
  {{-- dùng bootstrap-5 paginator để tránh CSS lạ --}}
  {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
