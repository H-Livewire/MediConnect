<div class="row">
    <div class="col-lg-12">
        <div class="section-title">
            <h2>{{ $title }}</h2>
            <img src="{{ asset('mediplus/img/section-img.png') }}" alt="">
            @isset($text)<p>{{ $text }}</p>@endisset
        </div>
    </div>
</div>
