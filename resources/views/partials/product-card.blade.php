<div class="col-6 col-md-3">
  <div class="card card-product h-100 shadow-sm position-relative">
    {{-- Badge giảm (%) --}}
    @if($p->discount_percent)
      <span class="badge position-absolute badge-sale" style="left:10px; top:10px;">-{{ $p->discount_percent }}%</span>
    @endif
    {{-- Ảnh --}}
    <div class="position-relative">
    @php
      $src = $p->image
          ? (\Illuminate\Support\Str::startsWith($p->image, ['http://','https://'])
              ? $p->image
              : asset('storage/'.$p->image))
          : 'https://placehold.co/600x600?text=Flower';
    @endphp
      <img src="{{ $p->image ? asset('storage/'.$p->image) : 'https://placehold.co/600x600?text=Flower' }}"
           class="thumb" alt="">
      {{-- HẾT HÀNG overlay --}}
      @if($p->stock <= 0)
        <div class="position-absolute top-50 start-50 translate-middle w-100 text-center"
             style="background:rgba(0,0,0,.35); color:#fff; padding:.4rem 0;">
          <strong>HẾT HÀNG</strong>
        </div>
      @endif
    </div>

    <div class="card-body d-flex flex-column">
      <h6 class="card-title mb-1">{{ $p->name }}</h6>
      <small class="text-muted">{{ $p->category->name ?? '' }}</small>
      <div class="mt-auto">
        <div class="price">
          {{ vnd($p->sale_price ?? $p->price) }}
          @if($p->sale_price)<span class="old-price">{{ vnd($p->price) }}</span>@endif
        </div>

        <div class="d-flex gap-2 mt-2">
          <a href="{{ route('products.show',$p) }}" class="btn btn-sm btn-brand flex-grow-1">Xem</a>

          {{-- Nút trái tim: thêm nhanh vào giỏ (qty=1) --}}
          <form action="{{ route('cart.add',$p) }}" method="post">
            @csrf
            <input type="hidden" name="qty" value="1">
            <button class="btn btn-sm btn-outline-secondary" title="Thêm vào giỏ">
              <i class="bi bi-heart"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
