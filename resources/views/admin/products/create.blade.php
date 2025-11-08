@extends('layouts.app')
@section('title','Thêm sản phẩm')
@section('content')
<h4 class="mb-3">Thêm sản phẩm</h4>
@if($errors->any())
  <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form action="{{ url('admin/products') }}" method="post" enctype="multipart/form-data">
  @include('admin.products._form', ['product'=>null])
</form>
@endsection
