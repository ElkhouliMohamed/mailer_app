@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="container-fluid p-4">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-8 col-lg-6">
                <div class="card radius-12 shadow-xl p-6 bg-white dark-bg-neutral-800">
                    <div class="text-center mb-6">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-120-px mb-5 light-logo">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" class="w-120-px mb-5 dark-logo" style="display: none;">
                        <h2 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">Verify Your Email</h2>
                        <p class="text-md text-secondary-light dark-text-neutral-300">Check your email for a verification link.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success dark-alert-success mb-5 radius-8">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="text-center mb-5">
                        <p class="text-md text-secondary-light dark-text-neutral-300">
                            Before proceeding, please verify your email by clicking the link we sent to <strong>{{ auth()->user()->email }}</strong>.
                        </p>
                    </div>

                    <!-- Resend Form -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 w-full py-14 radius-8 fw-semibold text-lg">
                            Resend Verification Email
                        </button>
                    </form>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-5">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger dark-btn-outline-danger w-full py-14 radius-8 fw-semibold text-lg">
                            Log Out
                        </button>
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
