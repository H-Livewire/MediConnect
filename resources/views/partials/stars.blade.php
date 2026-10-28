@php
    $value = (float) ($rating ?? 0);
    $total = (int) ($count ?? 0);
@endphp
<div class="mc-rating" title="{{ $total ? number_format($value, 1).' / 5' : 'No ratings yet' }}">
    <span class="mc-stars" aria-hidden="true">
        @for ($i = 1; $i <= 5; $i++)
            @if ($value >= $i)
                <i class="fa fa-star"></i>
            @elseif ($value >= $i - 0.5)
                <i class="fa fa-star-half-o"></i>
            @else
                <i class="fa fa-star-o"></i>
            @endif
        @endfor
    </span>
    <span class="mc-rating-text">
        @if ($total)
            {{ number_format($value, 1) }} <small>({{ $total }})</small>
        @else
            <small>No ratings yet</small>
        @endif
    </span>
</div>
