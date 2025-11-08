@extends('layouts.app')
@section('title','Sửa sản phẩm')
@section('content')
<h4 class="mb-3">Sửa sản phẩm: {{ $product->name }}</h4>
@if($errors->any())
  <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form action="{{ url('admin/products/'.$product->id) }}" method="post" enctype="multipart/form-data">
  @method('PUT')
  @include('admin.products._form', ['product'=>$product])
</form>
@endsection
