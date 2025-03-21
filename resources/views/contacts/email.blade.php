@extends('layouts.app')

@section('title', 'Send Email')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:mailbox-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Send Email to {{ $contact->name }}
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Compose and send an email</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                Back to Contacts
            </a>
        </div>

        <!-- Email Form -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <form method="POST" action="{{ route('contacts.sendEmail') }}" id="sendEmailForm">
                @csrf
                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                <div class="mb-5">
                    <label for="smtp_setting_id" class="form-label text-lg fw-medium text-black dark-text-white">SMTP Setting</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:settings-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <select name="smtp_setting_id" id="smtp_setting_id" class="form-select radius-8 text-lg py-12" required>
                            <option value="">Select SMTP Setting</option>
                            @foreach($smtpSettings as $smtp)
                                <option value="{{ $smtp->id }}">{{ $smtp->name }}</option>
                            @endforeach
                        </select>
                        @error('smtp_setting_id')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="subject" class="form-label text-lg fw-medium text-black dark-text-white">Subject</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:text-bold-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="subject" id="subject" class="form-control radius-8 text-lg py-12" value="{{ old('subject') }}" required>
                        @error('subject')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="content" class="form-label text-lg fw-medium text-black dark-text-white">Content</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:notes-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <textarea name="content" id="content" class="form-control radius-8 text-lg py-12" rows="5" required>{{ old('content') }}</textarea>
                        @error('content')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-success-600 dark-btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:mailbox-outline" class="text-lg"></iconify-icon>
                        Send Email
                    </button>
                    <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('sendEmailForm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to send this email?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, send it!',
                        cancelButtonText: 'No, cancel',
                        customClass: {
                            popup: 'dark:bg-neutral-800 dark:text-white',
                            confirmButton: 'btn btn-success-600 dark-btn-success-500 py-8 px-16 radius-6',
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
