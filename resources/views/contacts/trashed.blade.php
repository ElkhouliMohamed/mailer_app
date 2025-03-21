@extends('layouts.app')

@section('title', 'Trashed Contacts')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Trashed Contacts
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Manage deleted contacts</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                Back to Contacts
            </a>
        </div>

        <!-- Trashed Contacts Table -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <div class="table-responsive">
                <table id="trashed-contacts-table" class="table table-hover w-full">
                    <thead>
                    <tr class="bg-neutral-100 dark-bg-neutral-700">
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Full Name</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Email</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($contacts as $contact)
                        <tr class="border-b border-neutral-200 dark-border-neutral-600">
                            <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->first_name }} {{ $contact->last_name }}</td>
                            <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->email }}</td>
                            <td class="py-12 px-16">
                                <div class="d-flex gap-2">
                                    <form action="{{ route('contacts.restore', $contact->id) }}" method="POST" class="restore-contact-form d-inline" data-contact-id="{{ $contact->id }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success-600 dark-btn-success-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:undo-left-outline" class="text-md"></iconify-icon>
                                            Restore
                                        </button>
                                    </form>
                                    <form action="{{ route('contacts.forceDelete', $contact->id) }}" method="POST" class="force-delete-contact-form d-inline" data-contact-id="{{ $contact->id }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger-600 dark-btn-danger-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md"></iconify-icon>
                                            Delete Permanently
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-md text-secondary-light dark-text-neutral-300 py-16">No trashed contacts found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Minimal DataTable initialization to avoid data mismatch
                $('#trashed-contacts-table').DataTable({
                    paging: false, // Disable DataTables pagination since Laravel handles it
                    searching: true,
                    ordering: true,
                    info: false,
                    responsive: true,
                    language: {
                        search: '_INPUT_',
                        searchPlaceholder: 'Search trashed contacts...'
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-4"lf>t<"d-flex justify-content-between mt-4"i>',
                    drawCallback: function() {
                        attachRestoreListeners();
                        attachForceDeleteListeners();
                    }
                });

                // Attach SweetAlert to restore buttons
                function attachRestoreListeners() {
                    document.querySelectorAll('.restore-contact-form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const contactId = this.getAttribute('data-contact-id');
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Do you want to restore this contact?',
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
                }

                // Attach SweetAlert to force delete buttons
                function attachForceDeleteListeners() {
                    document.querySelectorAll('.force-delete-contact-form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const contactId = this.getAttribute('data-contact-id');
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'This contact will be permanently deleted!',
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
                }

                // Initial listeners
                attachRestoreListeners();
                attachForceDeleteListeners();

                // Success alert
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
