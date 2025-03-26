@extends('layouts.app')

@section('title', 'Create SMTP Settings')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:mailbox-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Create SMTP Settings
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Configure new email settings</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                Back to Dashboard
            </a>
        </div>

        <!-- Create Form -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <form method="POST" action="{{ route('smtp_settings.store') }}" id="smtpSettingsForm">
                @csrf

                <!-- Host -->
                <div class="mb-5">
                    <label for="host" class="form-label text-lg fw-medium text-black dark-text-white">Host</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:server-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="host" id="host" class="form-control radius-8 text-lg py-12" value="{{ old('host') }}" required>
                        @error('host')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Port -->
                <div class="mb-5">
                    <label for="port" class="form-label text-lg fw-medium text-black dark-text-white">Port</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:plug-circle-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="number" name="port" id="port" class="form-control radius-8 text-lg py-12" value="{{ old('port') }}" required min="1" max="65535">
                        @error('port')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Encryption -->
                <div class="mb-5">
                    <label for="encryption" class="form-label text-lg fw-medium text-black dark-text-white">Encryption</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:lock-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <select name="encryption" id="encryption" class="form-select radius-8 text-lg py-12">
                            <option value="" {{ old('encryption') == '' ? 'selected' : '' }}>None</option>
                            <option value="tls" {{ old('encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                        @error('encryption')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Username -->
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

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="form-label text-lg fw-medium text-black dark-text-white">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:key-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="password" name="password" id="password" class="form-control radius-8 text-lg py-12" value="{{ old('password') }}" required>
                        @error('password')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sender Name -->
                <div class="mb-5">
                    <label for="sender_name" class="form-label text-lg fw-medium text-black dark-text-white">Sender Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:user-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="sender_name" id="sender_name" class="form-control radius-8 text-lg py-12" value="{{ old('sender_name') }}" required>
                        @error('sender_name')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sender Email -->
                <div class="mb-5">
                    <label for="sender_email" class="form-label text-lg fw-medium text-black dark-text-white">Sender Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:mailbox-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="email" name="sender_email" id="sender_email" class="form-control radius-8 text-lg py-12" value="{{ old('sender_email') }}" required>
                        @error('sender_email')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:check-circle-outline" class="text-lg"></iconify-icon>
                        Save Settings
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('smtpSettingsForm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to save these SMTP settings?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'No, cancel',
                        customClass: {
                            popup: 'dark:bg-neutral-800 dark:text-white',
                            confirmButton: 'btn btn-primary-600 dark-btn-primary-500 py-8 px-16 radius-6',
                            cancelButton: 'btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-8 px-16 radius-6'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
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
