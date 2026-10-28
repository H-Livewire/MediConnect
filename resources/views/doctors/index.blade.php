@extends('layouts.app')
@section('title', 'Doctors')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Our Doctors'])

<section class="team single-page section">
    <div class="container">
        <form method="GET" action="{{ route('doctors.index') }}" class="mc-filter appointment">
            <div class="form row">
                <div class="col-lg-4 col-12 form-group"><input type="text" name="q" placeholder="Search by doctor name" value="{{ request('q') }}"></div>
                <div class="col-lg-3 col-md-6 col-12 form-group">
                    <select name="specialty">
                        <option value="">All specialties</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected(request('specialty') == $specialty->id)>{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-12 form-group">
                    <select name="hospital">
                        <option value="">All hospitals</option>
                        @foreach ($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" @selected(request('hospital') == $hospital->id)>{{ $hospital->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-12 form-group"><div class="button"><button type="submit" class="btn">Search</button></div></div>
            </div>
        </form>

        <div class="row">
            @forelse ($doctors as $doctor)
                @include('partials.doctor-card', ['doctor' => $doctor])
            @empty
                <div class="col-12"><p class="mc-empty text-center">No doctors match your search.</p></div>
            @endforelse
        </div>
        <div class="mc-pagination">{{ $doctors->links('partials.pagination') }}</div>
    </div>
</section>
@endsection
