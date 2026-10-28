@extends('layouts.app')
@section('title', 'Hospitals')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Hospitals'])

<section class="portfolio section">
    <div class="container">
        <form method="GET" action="{{ route('hospitals.index') }}" class="mc-filter appointment">
            <div class="form row">
                <div class="col-lg-10 col-md-9 col-12 form-group"><input type="text" name="q" placeholder="Search hospital or city" value="{{ request('q') }}"></div>
                <div class="col-lg-2 col-md-3 col-12 form-group"><div class="button"><button type="submit" class="btn">Search</button></div></div>
            </div>
        </form>

        <div class="row">
            @forelse ($hospitals as $hospital)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-service mc-hospital-card">
                        <img src="{{ $hospital->imageUrl() }}" alt="{{ $hospital->name }}">
                        <h4><a href="{{ route('hospitals.show', $hospital) }}">{{ $hospital->name }}</a></h4>
                        <p><i class="fa fa-map-marker"></i> {{ $hospital->city }} — {{ $hospital->address }}</p>
                        <p>{{ $hospital->doctors_count }} {{ Str::plural('doctor', $hospital->doctors_count) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="mc-empty text-center">No hospitals found.</p></div>
            @endforelse
        </div>
        <div class="mc-pagination">{{ $hospitals->links('partials.pagination') }}</div>
    </div>
</section>
@endsection
