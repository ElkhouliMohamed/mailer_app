@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="container-fluid p-4">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card radius-8 shadow-lg p-5 bg-white">
                    <div class="text-center mb-5">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-100-px mb-4">
                        <h1 class="text-3xl fw-semibold text-primary-600">Welcome to {{ config('app.name', 'Emailing App') }}</h1>
                        <p class="text-md text-secondary-light mt-2">Manage your contacts and emails with ease.</p>
                    </div>
                    <div class="d-flex flex-column gap-4 align-items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary-600 w-200-px py-12 px-24 radius-8 fw-semibold">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary-600 w-200-px py-12 px-24 radius-8 fw-semibold">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary-600 w-200-px py-12 px-24 radius-8 fw-semibold">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
