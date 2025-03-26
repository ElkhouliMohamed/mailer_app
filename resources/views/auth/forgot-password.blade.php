@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="container-fluid p-4">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card radius-12 shadow-xl p-6 bg-white dark-bg-neutral-800">
                    <div class="text-center mb-6">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-120-px mb-5 light-logo">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" class="w-120-px mb-5 dark-logo" style="display: none;">
                        <h2 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">Forgot Password</h2>
                        <p class="text-md text-secondary-light dark-text-neutral-300">Reset your {{ config('app.name') }} password</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success dark-alert-success mb-5 radius-8">{{ session('status') }}</div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger dark-alert-danger mb-5 radius-8">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-5">
                            <label for="email" class="form-label text-lg fw-medium text-black dark-text-white">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                                    <iconify-icon icon="mage:email" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                                </span>
                                <input type="email" name="email" id="email" class="form-control radius-8 text-lg py-12" value="{{ old('email') }}" required autofocus autocomplete="email">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 w-full py-14 radius-8 fw-semibold text-lg">
                            Send Password Reset Link
                        </button>

                        <p class="text-center text-md text-secondary-light dark-text-neutral-300 mt-5">
                            Back to <a href="{{ route('login') }}" class="text-primary-600 dark-text-primary-400 hover-text-primary-700 dark-hover-text-primary-300">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Toggle logo based on theme
            document.addEventListener('DOMContentLoaded', () => {
                const html = document.documentElement;
                const lightLogo = document.querySelector('.light-logo');
                const darkLogo = document.querySelector('.dark-logo');
                const theme = html.getAttribute('data-theme');
                if (theme === 'dark') {
                    lightLogo.style.display = 'none';
                    darkLogo.style.display = 'block';
                } else {
                    lightLogo.style.display = 'block';
                    darkLogo.style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection
