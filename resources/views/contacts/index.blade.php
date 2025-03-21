@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:users-group-rounded-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Contacts
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Manage your email contacts</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:add-circle-outline" class="text-lg"></iconify-icon>
                    Add Contact
                </a>
                <a href="{{ route('contacts.trashed') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-lg"></iconify-icon>
                    Trashed Contacts
                </a>
            </div>
        </div>

        <!-- Contacts Table -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <form method="POST" action="{{ route('contacts.bulkSend') }}" id="bulkSendForm">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex gap-3">
                        <select name="smtp_setting_id" class="form-select radius-8 py-12 px-16 text-md" required>
                            <option value="">Select SMTP Setting</option>
                            @foreach(\App\Models\SmtpSetting::all() as $smtp)
                                <option value="{{ $smtp->id }}">{{ $smtp->sender_name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="subject" class="form-control radius-8 py-12 px-16 text-md" placeholder="Subject" required>
                        <textarea name="content" class="form-control radius-8 py-12 px-16 text-md" placeholder="Email Content" rows="1" required></textarea>
                        <button type="submit" class="btn btn-success-600 dark-btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:mailbox-outline" class="text-lg"></iconify-icon>
                            Send Bulk Email
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="contacts-table" class="table table-hover w-full">
                        <thead>
                        <tr class="bg-neutral-100 dark-bg-neutral-700">
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Full Name</th>
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Email</th>
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Phone</th>
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Category</th>
                            <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($contacts as $contact)
                            <tr class="border-b border-neutral-200 dark-border-neutral-600">
                                <td class="py-12 px-16">
                                    <input type="checkbox" name="selected[]" value="{{ $contact->id }}" class="contact-checkbox">
                                </td>
                                <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->first_name }} {{ $contact->last_name }}</td>
                                <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->email }}</td>
                                <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->phone ?? 'N/A' }}</td>
                                <td class="text-md text-black dark-text-white py-12 px-16">{{ $contact->category ? $contact->category->name : 'Uncategorized' }}</td>
                                <td class="py-12 px-16">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('contacts.email', $contact) }}" class="btn btn-info-600 dark-btn-info-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:mailbox-outline" class="text-md"></iconify-icon>
                                            Email
                                        </a>
                                        <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-warning-500 dark-btn-warning-400 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:pen-outline" class="text-md"></iconify-icon>
                                            Edit
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="delete-contact-form d-inline" data-contact-id="{{ $contact->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-600 dark-btn-danger-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md"></iconify-icon>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-md text-secondary-light dark-text-neutral-300 py-16">No contacts found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $contacts->links() }}
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTable
                $('#contacts-table').DataTable({
                    paging: false, // Let Laravel handle pagination
                    searching: true,
                    ordering: true,
                    info: false,
                    responsive: true,
                    language: {
                        search: '_INPUT_',
                        searchPlaceholder: 'Search contacts...'
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-4"lf>t<"d-flex justify-content-between mt-4"i>',
                    drawCallback: function() {
                        attachDeleteListeners();
                        attachBulkSendListener();
                    }
                });

                // Select all checkboxes
                document.getElementById('select-all').addEventListener('change', function() {
                    document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Attach SweetAlert to delete buttons
                function attachDeleteListeners() {
                    document.querySelectorAll('.delete-contact-form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const contactId = this.getAttribute('data-contact-id');
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'This contact will be moved to trash!',
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

                // Attach SweetAlert to bulk send
                function attachBulkSendListener() {
                    const form = document.getElementById('bulkSendForm');
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const selectedCount = document.querySelectorAll('.contact-checkbox:checked').length;
                        if (selectedCount === 0) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Please select at least one contact.',
                                icon: 'error',
                                customClass: {
                                    popup: 'dark:bg-neutral-800 dark:text-white',
                                    confirmButton: 'btn btn-primary-600 dark-btn-primary-500 py-8 px-16 radius-6'
                                },
                                buttonsStyling: false
                            });
                            return;
                        }
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `Send email to ${selectedCount} contact${selectedCount > 1 ? 's' : ''}?`,
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
                }

                // Initial listeners
                attachDeleteListeners();
                attachBulkSendListener();

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
