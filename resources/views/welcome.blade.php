@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="container-fluid p-0 rounded-sm">
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-gray-500 to-gray-700 text-white py-24 shadow-xl ">
            <div class="container mx-auto px-6 rounded-sm">
                <div class="row justify-content-center align-items-center rounded-sm">
                    <div class="col-md-10 col-lg-8 text-center">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-150-px mx-auto mb-8 animate-fade-in">
                        <h1 class="text-4xl md:text-5xl fw-bold mb-6 animate-slide-up">
                            Welcome to Emailing App
                        </h1>
                        <p class="text-lg md:text-xl text-gray-100 mb-10 animate-slide-up delay-100">
                            Streamline your contact management and email workflows with ease.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-6 animate-slide-up delay-200">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-white w-220-px py-14 px-28 radius-8 fw-semibold text-gray-700 hover:bg-gray-200 transition-all">
                                    <iconify-icon icon="solar:dashboard-outline" class="text-lg mr-2"></iconify-icon>
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-white w-220-px py-14 px-28 radius-8 fw-semibold text-gray-700 hover:bg-gray-200 transition-all">
                                    <iconify-icon icon="solar:login-outline" class="text-lg mr-2"></iconify-icon>
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-white w-220-px py-14 px-28 radius-8 fw-semibold text-white hover:bg-white hover:text-gray-700 transition-all">
                                        <iconify-icon icon="solar:user-plus-outline" class="text-lg mr-2"></iconify-icon>
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-gray-200">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl fw-semibold text-gray-700 text-center mb-16">
                    Why Choose Emailing App
                </h2>
                <div class="row g-8">
                    <div class="col-md-4">
                        <div class="card radius-12 shadow-md bg-white p-8 text-center transition-all hover:shadow-xl">
                            <div class="w-70-px h-70-px bg-gray-100 text-gray-600 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-6">
                                <iconify-icon icon="solar:users-group-rounded-outline" class="text-3xl"></iconify-icon>
                            </div>
                            <h3 class="text-xl fw-semibold text-black mb-4">Contact Management</h3>
                            <p class="text-md text-secondary-light">
                                Organize your contacts into categories and manage them effortlessly.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-12 shadow-md bg-white p-8 text-center transition-all hover:shadow-xl">
                            <div class="w-70-px h-70-px bg-gray-100 text-gray-600 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-6">
                                <iconify-icon icon="solar:mailbox-outline" class="text-3xl"></iconify-icon>
                            </div>
                            <h3 class="text-xl fw-semibold text-black mb-4">Email Tracking</h3>
                            <p class="text-md text-secondary-light">
                                Monitor sent, pending, and failed emails with detailed logs.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card radius-12 shadow-md bg-white p-8 text-center transition-all hover:shadow-xl">
                            <div class="w-70-px h-70-px bg-gray-100 text-gray-600 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-6">
                                <iconify-icon icon="solar:export-outline" class="text-3xl"></iconify-icon>
                            </div>
                            <h3 class="text-xl fw-semibold text-black mb-4">Import/Export</h3>
                            <p class="text-md text-secondary-light">
                                Easily import and export contacts with flexible category support.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="card radius-12 shadow-md bg-white p-8 text-center transition-all hover:shadow-xl">
                            <div class="w-70-px h-70-px bg-gray-100 text-gray-600 rounded-circle d-flex justify-content-center align-items-center mx-auto mb-6">
                                <iconify-icon icon="solar:settings-outline" class="text-3xl"></iconify-icon>
                            </div>
                            <h3 class="text-xl fw-semibold text-black mb-4">SMTP Settings</h3>
                            <p class="text-md text-secondary-light">
                                Add, remove, or update SMTP settings directly through the app interface.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class=" bg-gray-100 mt-4 shadow-xl p-36">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl fw-semibold text-gray-700 mb-8">
                    Ready to Get Started?
                </h2>
                <p class="text-lg text-secondary-light mb-10 max-w-700-px mx-auto">
                    Join thousands of users who trust {{ config('app.name', 'Emailing App') }} to manage their contacts, emails, and SMTP settings efficiently.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-gray-600 w-220-px py-14 px-28 radius-8 fw-semibold text-white hover:bg-gray-700 transition-all">
                            <iconify-icon icon="solar:dashboard-outline" class="text-lg mr-2"></iconify-icon>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gray-600 w-220-px py-14 px-28 radius-8 fw-semibold text-white hover:bg-gray-700 transition-all">
                            <iconify-icon icon="solar:login-outline" class="text-lg mr-2"></iconify-icon>
                            Login Now
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-gray-600 w-220-px py-14 px-28 radius-8 fw-semibold text-gray-600 hover:bg-gray-600 hover:text-white transition-all">
                                <iconify-icon icon="solar:user-plus-outline" class="text-lg mr-2"></iconify-icon>
                                Sign Up
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>
    </div>

    <!-- Custom CSS for Animations and Spacing -->
    @push('styles')
        <style>
            .animate-fade-in {
                animation: fadeIn 1s ease-in-out;
            }
            .animate-slide-up {
                animation: slideUp 0.8s ease-out;
            }
            .delay-100 {
                animation-delay: 0.1s;
            }
            .delay-200 {
                animation-delay: 0.2s;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            /* Ensure container has enough room */
            .container {
                max-width: 1200px;
            }
            /* Adjust button styles */
            .btn-gray-600 {
                background-color: #4b5563; /* Tailwind gray-600 */
                border-color: #4b5563;
            }
            .btn-outline-gray-600 {
                border-color: #4b5563;
                color: #4b5563;
            }
            .btn-gray-600:hover {
                background-color: #374151; /* Tailwind gray-700 */
                border-color: #374151;
            }
        </style>
    @endpush
@endsection
