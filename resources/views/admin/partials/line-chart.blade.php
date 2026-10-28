@php
    $w = 640; $h = 250; $l = 38; $r = 14; $t = 14; $b = 30;
    $maxValue = max(4, (int) collect($series)->pluck('values')->flatten()->max());
    $top = (int) (ceil($maxValue / 4) * 4);
    $n = count($labels);
    $px = fn (int $i): float => $l + ($n > 1 ? $i * ($w - $l - $r) / ($n - 1) : ($w - $l - $r) / 2);
    $py = fn (int|float $v): float => $t + (1 - $v / $top) * ($h - $t - $b);
@endphp
<svg viewBox="0 0 {{ $w }} {{ $h }}" class="ad-chart" role="img" aria-label="{{ $title }}">
    @for ($i = 0; $i <= 4; $i++)
        @php($gv = $top / 4 * $i)
        <line class="grid" x1="{{ $l }}" x2="{{ $w - $r }}" y1="{{ round($py($gv), 1) }}" y2="{{ round($py($gv), 1) }}"/>
        <text x="{{ $l - 8 }}" y="{{ round($py($gv) + 4, 1) }}" text-anchor="end">{{ $gv == (int) $gv ? (int) $gv : $gv }}</text>
    @endfor
    @foreach ($labels as $i => $label)
        <text x="{{ round($px($i), 1) }}" y="{{ $h - 8 }}" text-anchor="middle">{{ $label }}</text>
    @endforeach
    @foreach ($series as $si => $s)
        @php
            $points = collect($s['values'])->map(fn ($v, $i) => round($px($i), 1).' '.round($py($v), 1));
            $d = 'M'.$points->implode(' L');
        @endphp
        @if ($si === 0 && $n > 1)
            <path class="area-a" d="{{ $d }} L{{ round($px($n - 1), 1) }} {{ round($py(0), 1) }} L{{ round($px(0), 1) }} {{ round($py(0), 1) }}Z"/>
        @endif
        <path class="{{ $si === 0 ? 'line-a' : 'line-b' }}" d="{{ $d }}"/>
        @if ($si === 0)
            @foreach ($s['values'] as $i => $v)
                <circle class="dot-a" cx="{{ round($px($i), 1) }}" cy="{{ round($py($v), 1) }}" r="3.5"><title>{{ $labels[$i] }}: {{ $v }}</title></circle>
            @endforeach
        @endif
    @endforeach
</svg>
