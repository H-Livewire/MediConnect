@php
    $total = collect($segments)->sum('value');
    $offset = 25;
@endphp
<div class="ad-donut-wrap">
    <svg viewBox="0 0 36 36" class="ad-donut" role="img" aria-label="Appointment status distribution">
        <circle class="track" cx="18" cy="18" r="15.9155"/>
        @foreach ($segments as $seg)
            @php($pct = $total > 0 ? $seg['value'] / $total * 100 : 0)
            @if ($pct > 0)
                <circle cx="18" cy="18" r="15.9155" stroke="{{ $seg['color'] }}" stroke-dasharray="{{ round($pct, 2) }} {{ round(100 - $pct, 2) }}" stroke-dashoffset="{{ round($offset - 25, 2) * -1 }}"/>
                @php($offset += $pct)
            @endif
        @endforeach
    </svg>
    <div class="ad-donut-center"><strong>{{ $total }}</strong><span>Total</span></div>
</div>
<div class="ad-legend" style="justify-content:center">
    @foreach ($segments as $seg)
        <span><i style="background: {{ $seg['color'] }}"></i>{{ $seg['label'] }} {{ $total > 0 ? round($seg['value'] / $total * 100) : 0 }}%</span>
    @endforeach
</div>
