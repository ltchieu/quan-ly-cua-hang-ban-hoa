@props(['value' => 0, 'size' => 16])
@php $v = max(0, min(5, (float)$value)); @endphp
<span title="{{ $v }}/5">
  @for($i=1;$i<=5;$i++)
    @php $full = $v >= $i; $half = !$full && $v >= $i-0.5; @endphp
    <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
         fill="{{ $full ? '#ff7a00' : ($half ? 'url(#half)' : 'none') }}" stroke="#ff7a00" stroke-width="1.5" style="margin-right:2px">
      <defs>
        <linearGradient id="half"><stop offset="50%" stop-color="#ff7a00"/><stop offset="50%" stop-color="transparent"/></linearGradient>
      </defs>
      <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
    </svg>
  @endfor
</span>
