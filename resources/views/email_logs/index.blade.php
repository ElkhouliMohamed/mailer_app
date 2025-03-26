@extends('layouts.app')

@section('title', 'Email Logs')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:letter-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Email Logs
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Track your email sending history</p>
            </div>
            <div class="d-flex gap-3">
                <button onclick="location.reload()" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:refresh-outline" class="text-lg"></iconify-icon>
                    Refresh
                </button>
                <a href="{{ route('email-logs.index', ['trashed' => request('trashed') === 'true' ? null : 'true']) }}"
                   class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-lg"></iconify-icon>
                    {{ request('trashed') === 'true' ? 'Active Logs' : 'Trashed Logs' }}
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 mb-6">
            <form method="GET" action="{{ route('email-logs.index') }}" class="d-flex gap-3 align-items-center flex-wrap">
                <input type="text" name="search" class="form-control radius-8 py-12 px-16 text-md"
                       placeholder="Search by subject, contact..." value="{{ request('search') }}">
                <select name="status" class="form-select radius-8 py-12 px-16 text-md">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <select name="per_page" class="form-select radius-8 py-12 px-16 text-md">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 per page</option>
                </select>
                @if(request('trashed') === 'true')
                    <input type="hidden" name="trashed" value="true">
                @endif
                <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md">
                    Search
                </button>
            </form>
        </div>

        <!-- Email Logs Table -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <div class="table-responsive">
                <table id="email-logs-table" class="table table-hover w-full">
                    <thead>
                    <tr class="bg-neutral-100 dark-bg-neutral-700">
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">ID</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Contact</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Subject</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Status</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Created At</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($emailLogs as $log)
                        <tr class="border-b border-neutral-200 dark-border-neutral-600">
                            <td class="text-md text-black dark-text-white py-12 px-16">{{ $log->id }}</td>
                            <td class="text-md text-black dark-text-white py-12 px-16">{{ $log->contact->first_name." ".$log->contact->last_name ?? 'N/A' }}</td>
                            <td class="text-md text-black dark-text-white py-12 px-16 truncate max-w-xs" title="{{ $log->subject }}">
                                {{ $log->subject }}
                            </td>
                            <td class="text-md text-black dark-text-white py-12 px-16">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $log->status === 'sent' ? 'bg-green-100 text-green-800' :
                                           ($log->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                            </td>
                            <td class="text-md text-black dark-text-white py-12 px-16">
                                {{ $log->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-12 px-16">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('email-logs.show', $log) }}"
                                       class="btn btn-info-600 dark-btn-info-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:eye-outline" class="text-md"></iconify-icon>
                                        View
                                    </a>
                                    @if(!$log->trashed())
                                        <form action="{{ route('email-logs.destroy', $log) }}" method="POST" class="delete-log-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-600 dark-btn-danger-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md"></iconify-icon>
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('email-logs.restore', $log) }}" method="POST" class="restore-log-form d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success-600 dark-btn-success-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                                <iconify-icon icon="solar:undo-outline" class="text-md"></iconify-icon>
                                                Restore
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-md text-secondary-light dark-text-neutral-300 py-16">
                                No email logs found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $emailLogs->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

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
