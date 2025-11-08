@csrf
<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Tên sản phẩm</label>
    <input name="name" value="{{ old('name',$product->name ?? '') }}" class="form-control" required>

    <label class="form-label mt-3">Slug (để trống sẽ tự tạo)</label>
    <input name="slug" value="{{ old('slug',$product->slug ?? '') }}" class="form-control">

    <label class="form-label mt-3">Mô tả</label>
    <textarea name="description" rows="5" class="form-control">{{ old('description',$product->description ?? '') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">Danh mục</label>
    <select name="category_id" class="form-select" required>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(old('category_id',$product->category_id ?? '')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>

    <div class="row mt-3 g-2">
      <div class="col-6">
        <label class="form-label">Giá gốc</label>
        <input name="price" type="number" min="0" value="{{ old('price',$product->price ?? 0) }}" class="form-control" required>
      </div>
      <div class="col-6">
        <label class="form-label">Giá KM</label>
        <input name="sale_price" type="number" min="0" value="{{ old('sale_price',$product->sale_price ?? null) }}" class="form-control">
      </div>
      <div class="col-6">
        <label class="form-label">Tồn kho</label>
        <input name="stock" type="number" min="0" value="{{ old('stock',$product->stock ?? 0) }}" class="form-control" required>
      </div>
      <div class="col-6 d-flex align-items-end">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                 {{ old('is_active',$product->is_active ?? 1) ? 'checked' : '' }}>
          <label for="is_active" class="form-check-label">Hiển thị</label>
        </div>
      </div>
    </div>

    <label class="form-label mt-3">Ảnh</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($product?->image))
      <div class="mt-2">
        <img src="{{ asset('storage/'.$product->image) }}" style="width:100%; max-width:220px; object-fit:cover" class="rounded border">
        <div class="form-text">Ảnh hiện tại</div>
      </div>
    @endif

    <button class="btn btn-brand w-100 mt-3">Lưu</button>
  </div>
</div>
