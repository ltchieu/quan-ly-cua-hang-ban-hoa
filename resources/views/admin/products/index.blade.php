@extends('layouts.admin')
@section('title','Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Quản lý sản phẩm</h4>
  <a href="{{ url('admin/products/create') }}" class="btn btn-brand">+ Thêm sản phẩm</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3"><input class="form-control" name="q" value="{{ $q }}" placeholder="Tìm theo tên..."></div>
  <div class="col-md-3">
    <select class="form-select" name="category">
      <option value="">-- Tất cả danh mục --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected($cat==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3 form-check d-flex align-items-center">
    <input type="checkbox" class="form-check-input me-2" name="inactive" id="inactive" value="1" @checked($onlyInactive)>
    <label for="inactive" class="form-check-label">Chỉ hiển thị sản phẩm ẩn</label>
  </div>
  <div class="col-md-3"><button class="btn btn-outline-secondary">Lọc</button></div>
</form>

<div class="table-responsive bg-white border rounded">
<table class="table align-middle mb-0">
  <thead><tr>
    <th>#</th><th>Ảnh</th><th>Tên</th><th>Danh mục</th><th>Giá</th><th>Tồn</th><th>Hiển thị</th><th style="width:160px"></th>
  </tr></thead>
  <tbody>
  @foreach($products as $p)
    <tr>
      <td>{{ $p->id }}</td>
      <td style="width:70px">
        <img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/80' }}" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover">
      </td>
      <td><a href="{{ route('products.show',$p) }}" target="_blank">{{ $p->name }}</a></td>
      <td>{{ $p->category->name ?? '' }}</td>
      <td>{{ vnd($p->sale_price ?? $p->price) }} @if($p->sale_price)<small class="text-muted text-decoration-line-through">{{ vnd($p->price) }}</small>@endif</td>
      <td>{{ $p->stock }}</td>
      <td>{!! $p->is_active ? '<span class="badge text-bg-success">Hiện</span>' : '<span class="badge text-bg-secondary">Ẩn</span>' !!}</td>
      <td class="text-end">
        <a href="{{ url('admin/products/'.$p->id.'/edit') }}" class="btn btn-sm btn-outline-primary">Sửa</a>
        <form action="{{ url('admin/products/'.$p->id) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Xóa</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>

<div class="mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
