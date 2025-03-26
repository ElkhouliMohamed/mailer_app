@extends('layouts.app')

@section('title', 'Email Log Details')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div class="flex items-center space-x-3">
                <div class="bg-primary-100 dark:bg-primary-900 rounded-full p-3">
                    <iconify-icon icon="solar:letter-outline" class="text-3xl text-primary-600 dark:text-primary-300"></iconify-icon>
                </div>
                <div>
                    <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                        Email Log #{{ $emailLog->id }}
                    </h1>
                    <p class="text-md text-secondary-light dark-text-neutral-300 mt-1">Detailed view of email log</p>
                </div>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('email-logs.index') }}"
                   class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2 hover:bg-neutral-100 dark-hover:bg-neutral-700 transition-colors duration-200">
                    <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Email Log Details -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-6 overflow-hidden">
            <div class="relative">
                <!-- Decorative Element -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary-100 dark:bg-primary-900 rounded-bl-full opacity-20"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative z-10">
                    <div class="space-y-6">
                        <div class="flex items-start space-x-3">
                            <iconify-icon icon="solar:user-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                            <div>
                                <label class="text-md fw-semibold text-black dark-text-white">Contact</label>
                                <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                    {{ $emailLog->contact->first_name." ".$emailLog->contact->last_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <iconify-icon icon="solar:text-bold-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                            <div>
                                <label class="text-md fw-semibold text-black dark-text-white">Subject</label>
                                <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                    {{ $emailLog->subject }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <iconify-icon icon="solar:check-circle-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                            <div>
                                <label class="text-md fw-semibold text-black dark-text-white">Status</label>
                                <p class="text-md mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $emailLog->status === 'sent' ? 'bg-green-100 text-green-800' :
                                           ($emailLog->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($emailLog->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <iconify-icon icon="solar:settings-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                            <div>
                                <label class="text-md fw-semibold text-black dark-text-white">SMTP Setting</label>
                                <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                    {{ $emailLog->smtpSetting->sender_name ?? 'N/A' }} ({{ $emailLog->smtpSetting->sender_email ?? 'N/A' }})
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-3">
                            <iconify-icon icon="solar:calendar-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                            <div>
                                <label class="text-md fw-semibold text-black dark-text-white">Created At</label>
                                <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                    {{ $emailLog->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($emailLog->attachment)
                            <div class="flex items-start space-x-3">
                                <iconify-icon icon="solar:paperclip-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                                <div>
                                    <label class="text-md fw-semibold text-black dark-text-white">Attachment</label>
                                    <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                        {{ $emailLog->attachment }}
                                        <!-- Uncomment and adjust if you store attachments -->
                                        <!-- <a href="{{ asset('storage/attachments/' . $emailLog->attachment) }}" class="text-primary-600 hover:underline ml-2">Download</a> -->
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($emailLog->trashed())
                            <div class="flex items-start space-x-3">
                                <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-xl text-primary-600 dark:text-primary-300 mt-1"></iconify-icon>
                                <div>
                                    <label class="text-md fw-semibold text-black dark-text-white">Deleted At</label>
                                    <p class="text-md text-secondary-light dark-text-neutral-300 mt-1 bg-neutral-50 dark-bg-neutral-700 px-3 py-2 rounded-lg">
                                        {{ $emailLog->deleted_at->format('M d, Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 relative z-10">
                    <label class="text-md fw-semibold text-black dark-text-white flex items-center gap-2">
                        <iconify-icon icon="solar:chat-square-outline" class="text-xl text-primary-600 dark:text-primary-300"></iconify-icon>
                        Content
                    </label>
                    <div class="mt-2 p-5 bg-neutral-100 dark-bg-neutral-700 rounded-lg text-md text-black dark-text-white shadow-inner border border-neutral-200 dark-border-neutral-600">
                        {!! nl2br(e($emailLog->content)) !!}
                    </div>
                </div>

                <div class="mt-8 d-flex gap-3 relative z-10">
                    @if(!$emailLog->trashed())
                        <form action="{{ route('email-logs.destroy', $emailLog) }}" method="POST" class="delete-log-form d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger-600 dark-btn-danger-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2 hover:bg-danger-700 transition-colors duration-200">
                                <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-lg"></iconify-icon>
                                Move to Trash
                            </button>
                        </form>
                    @else
                        <form action="{{ route('email-logs.restore', $emailLog) }}" method="POST" class="restore-log-form d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success-600 dark-btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2 hover:bg-success-700 transition-colors duration-200">
                                <iconify-icon icon="solar:undo-outline" class="text-lg"></iconify-icon>
                                Restore
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                position: relative;
                overflow: hidden;
            }
            .shadow-inner {
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-log-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This email log will be moved to trash!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, keep it',
                            customClass: {
                                popup: 'dark:bg-neutral-800 dark:text-white',
                                confirmButton: 'btn btn-danger-600 dark-btn-danger-500 py-8 px-16 radius-6',
                                cancelButton: 'btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-8 px-16 radius-6'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });

                document.querySelectorAll('.restore-log-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Restore this log?',
                            text: 'This email log will be restored!',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, restore it!',
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
                });
            });
        </script>
    @endpush
@endsection
