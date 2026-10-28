@extends('layouts.app')
@section('title', 'Afya Smart')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Afya Smart'])

<section class="services section">
    <div class="container">
        @include('partials.section-title', ['title' => 'Afya Smart Health Guide', 'text' => 'Not sure who to see? Pick the area that matches your concern and we will show you the right doctors.'])
        <div class="row">
            @foreach ($specialties as $specialty)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-service">
                        <i class="icofont {{ $specialty->icon }}"></i>
                        <h4><a href="{{ route('doctors.index', ['specialty' => $specialty->id]) }}">{{ $specialty->name }}</a></h4>
                        <p>{{ $specialty->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="text-center mc-muted mc-disclaimer">This guide is for general information only and is not a medical diagnosis. In an emergency call 114.</p>
    </div>
</section>
@endsection
