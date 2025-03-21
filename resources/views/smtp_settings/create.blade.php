@extends('layouts.app')

@section('title', 'Create SMTP Setting')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:settings-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Create SMTP Setting
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Add a new SMTP configuration</p>
            </div>
            <a href="{{ route('smtp_settings.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                Back to SMTP Settings
            </a>
        </div>

        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <form method="POST" action="{{ route('smtp_settings.store') }}" id="createSmtpForm">
                @csrf
                <div class="mb-5">
                    <label for="host" class="form-label text-lg fw-medium text-black dark-text-white">Host</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:server-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="host" id="host" class="form-control radius-8 text-lg py-12" value="{{ old('host') }}" required autofocus>
                        @error('host')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-5">
                    <label for="port" class="form-label text-lg fw-medium text-black dark-text-white">Port</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:link-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="number" name="port" id="port" class="form-control radius-8 text-lg py-12" value="{{ old('port') }}" required>
                        @error('port')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-5">
                    <label for="username" class="form-label text-lg fw-medium text-black dark-text-white">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:user-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="username" id="username" class="form-control radius-8 text-lg py-12" value="{{ old('username') }}" required>
                        @error('username')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label text-lg fw-medium text-black dark-text-white">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:lock-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="password" name="password" id="password" class="form-control radius-8 text-lg py-12" value="{{ old('password') }}" required>
                        @error('password')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:check-circle-outline" class="text-lg"></iconify-icon>
                        Save SMTP Setting
                    </button>
                    <a href="{{ route('smtp_settings.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('createSmtpForm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to create this SMTP setting?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, create it!',
                        cancelButtonText: 'No, cancel',
                        customClass: {
                            popup: 'dark:bg-neutral-800 dark:text-white',
                            confirmButton: 'btn btn-primary-600 dark-btn-primary-500 py-8 px-16 radius-6',
                            cancelButton: 'btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-8 px-16 radius-6'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });

                @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'dark:bg-neutral-800 dark:text-white',
                        confirmButton: 'btn btn-primary-600 dark-btn-primary-500 py-8 px-16 radius-6'
                    },
                    buttonsStyling: false
                });
                @endif
            });
        </script>
    @endpush
@endsection
