@extends('layouts.app')
@section('title','Trang chủ')

@section('content')
{{-- Slider --}}
<div id="hero" class="mb-4">
  <div id="heroCarousel" class="carousel slide shadow-sm rounded overflow-hidden">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://images.unsplash.com/photo-1464965911861-746a04b4bca6?q=80&w=1600" class="d-block w-100" style="max-height:420px; object-fit:cover;">
        <div class="carousel-caption text-start">
          <h3>Hoa tươi mỗi ngày</h3><p>Giao nhanh trong 2 giờ tại TP.HCM.</p>
          <a href="{{ route('products.index') }}" class="btn btn-brand btn-sm">Mua ngay</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?q=80&w=1600" class="d-block w-100" style="max-height:420px; object-fit:cover;">
        <div class="carousel-caption text-start"><h3>Thiết kế độc quyền</h3></div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
  </div>
</div>

{{-- Sản phẩm mới --}}
<h4 class="mb-3">Mới về</h4>
<div class="row g-3 mb-4">
  @foreach($featured as $p)
    @include('partials.product-card', ['p'=>$p])
  @endforeach
</div>

{{-- Các dải theo danh mục --}}
@foreach($sections as $cat)
  @php
    $items = \App\Models\Product::with('category')
      ->where('category_id',$cat->id)->latest()->take(8)->get();
  @endphp
  @if($items->isNotEmpty())
  <div class="py-3">
    <h4 class="mb-3 text-center" style="border-top:1px solid #e6e6e6; padding-top:12px;">
      {{ strtoupper($cat->name) }}
    </h4>
    <div class="row g-3">
      @foreach($items as $p)
        @include('partials.product-card', ['p'=>$p])
      @endforeach
    </div>
    <div class="text-center mt-3">
      <a href="{{ route('products.index',['category'=>$cat->id]) }}" class="btn btn-outline-secondary">
        Xem thêm {{ strtolower($cat->name) }}
      </a>
    </div>
  </div>
  @endif
@endforeach
@endsection
