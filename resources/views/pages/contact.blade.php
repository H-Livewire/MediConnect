@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
@include('partials.breadcrumbs', ['title' => 'Contact Us'])

<section class="contact-us section">
    <div class="container">
        <div class="inner">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-us-left">
                        <div class="mc-contact-card">
                            <h2>Get In Touch</h2>
                            <ul class="mc-contact-list">
                                <li><i class="fa fa-map-marker"></i>Dar es Salaam, Tanzania</li>
                                <li><i class="fa fa-phone"></i>+255 700 000 000</li>
                                <li><i class="fa fa-envelope"></i>support@mediconnect.co.tz</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-us-form">
                        <h2>Contact With Us</h2>
                        <p>If you have any questions please feel free to contact us.</p>
                        <form class="form" method="POST" action="{{ route('contact.send') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                                        @error('name')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                        @error('email')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" required>
                                        @error('subject')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea name="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                                        @error('message')<small class="mc-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn"><button class="btn" type="submit">Send</button></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
