@extends('layouts.app')
@section('title', $hospital->name)

@section('content')
@include('partials.breadcrumbs', ['title' => 'Hospital Details'])

<section class="portfolio-single section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12"><div class="mc-doctor-photo"><img src="{{ $hospital->imageUrl() }}" alt="{{ $hospital->name }}"></div></div>
            <div class="col-lg-6 col-12">
                <div class="mc-doctor-info">
                    <h2>{{ $hospital->name }}</h2>
                    <p>{{ $hospital->description }}</p>
                    <ul class="mc-facts">
                        <li><i class="fa fa-map-marker"></i>{{ $hospital->address }}, {{ $hospital->city }}</li>
                        @if ($hospital->phone)<li><i class="fa fa-phone"></i>{{ $hospital->phone }}</li>@endif
                        @if ($hospital->email)<li><i class="fa fa-envelope"></i>{{ $hospital->email }}</li>@endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="team section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Doctors At '.$hospital->name])
        <div class="row">
            @forelse ($hospital->doctors as $doctor)
                @php($doctor->setRelation('hospital', $hospital))
                @include('partials.doctor-card', ['doctor' => $doctor])
            @empty
                <div class="col-12"><p class="mc-empty text-center">No doctors listed yet.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
