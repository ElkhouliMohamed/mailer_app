@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark:text-primary-300">
                    <iconify-icon icon="solar:users-group-rounded-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Contacts
                </h1>
                <p class="text-md text-secondary-light dark:text-neutral-300 mt-2">Manage your email contacts</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary-600 dark:btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:add-circle-outline" class="text-lg"></iconify-icon>
                    Add Contact
                </a>
                <a href="{{ route('contacts.trashed') }}" class="btn btn-outline-neutral-600 dark:btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-lg"></iconify-icon>
                    Trashed Contacts
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card radius-12 shadow-xl bg-white dark:bg-neutral-800 p-5 mb-6">
            <form method="GET" action="{{ route('contacts.index') }}" class="d-flex gap-3 align-items-center flex-wrap" id="searchForm">
                <input type="text" name="search" class="form-control radius-8 py-12 px-16 text-md" placeholder="Search by name, email, etc." value="{{ request('search') }}">
                <select name="category_id" class="form-select radius-8 py-12 px-16 text-md">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="form-select radius-8 py-12 px-16 text-md">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 per page</option>
                </select>
                <button type="submit" class="btn btn-primary-600 dark:btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md" id="searchButton">Search</button>
            </form>
        </div>

        <!-- Bulk Send Form -->
        <div class="card radius-12 shadow-xl bg-white dark:bg-neutral-800 p-5">
            <form action="{{ route('contacts.bulkSend') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- SMTP Settings and Other Fields -->
                <div class="mb-4">
                    <label for="smtp_setting_id" class="text-md fw-semibold text-black dark:text-white">SMTP Setting</label>
                    <select name="smtp_setting_id" id="smtp_setting_id" class="form-select radius-8 py-12 px-16 text-md" required>
                        <option value="">Select SMTP Setting</option>
                        @foreach(\App\Models\SmtpSetting::all() as $smtp)
                            <option value="{{ $smtp->id }}">{{ $smtp->sender_name }} ({{ $smtp->sender_email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="subject" class="text-md fw-semibold text-black dark:text-white">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control radius-8 py-12 px-16 text-md" placeholder="Subject" required>
                </div>

                <div class="mb-4">
                    <label for="content" class="text-md fw-semibold text-black dark:text-white">Content</label>
                    <textarea name="content" id="content" class="form-control radius-8 py-12 px-16 text-md" placeholder="Email Content" rows="3" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="attachment" class="text-md fw-semibold text-black dark:text-white">Attachment (optional)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control radius-8 py-12 px-16 text-md" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <!-- Contact Checkboxes with Select All -->
                <div class="mb-4">
                    <h3 class="text-md fw-semibold text-black dark:text-white mb-2">Select Contacts</h3>
                    <div class="mb-3">
                        <input type="checkbox" id="select-all" class="checkboxOfContacts">
                        <label for="select-all" class="text-md fw-semibold text-black dark:text-white cursor-pointer">Select All</label>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @foreach($contacts as $contact)
                            <div class="mb-2 flex items-center gap-3">
                                <input type="checkbox"
                                       name="contact_ids[]"
                                       value="{{ $contact->id }}"
                                       id="contact-{{ $contact->id }}"
                                       class="checkboxOfContacts contact-checkbox">
                                <label for="contact-{{ $contact->id }}" class="text-md text-black dark:text-white cursor-pointer hover:text-primary-600 dark:hover:text-primary-300 flex-grow">
                                    {{ $contact->first_name }} {{ $contact->last_name }} ({{ $contact->email }})
                                </label>
                                <div class="flex gap-2">
                                    <a href="{{ route('contacts.email', $contact) }}" class="btn btn-info-600 dark:btn-info-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:mailbox-outline" class="text-md"></iconify-icon>
                                        Email
                                    </a>
                                    <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-warning-500 dark:btn-warning-400 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:pen-outline" class="text-md"></iconify-icon>
                                        Edit
                                    </a>
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="delete-contact-form d-inline" data-contact-id="{{ $contact->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger-600 dark:btn-danger-500 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md"></iconify-icon>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        @if($contacts->isEmpty())
                            <p class="text-md text-secondary-light dark:text-neutral-300">No contacts found.</p>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success-600 dark:btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:mailbox-outline" class="text-lg"></iconify-icon>
                    Send Bulk Email
                </button>

                <!-- Feedback Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger dark:alert-danger-600 mt-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success dark:alert-success-600 mt-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger dark:alert-danger-600 mt-4">
                        {{ session('error') }}
                    </div>
                @endif
            </form>

            <!-- Pagination Outside Form -->
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .checkboxOfContacts {
                width: 16px;
                height: 16px;
                border-radius: 2px;
                border: 1px solid #e2e8f0;
                background-color: #fff;
                margin-right: 10px;
                cursor: pointer;
                accent-color: #2563eb; /* Tailwind's blue-600 */
            }
            .checkboxOfContacts:checked {
                background-color: #2563eb;
                border-color: #2563eb;
            }
            .max-h-64 {
                max-height: 16rem; /* Limits height for scrollable list */
            }
            .overflow-y-auto {
                overflow-y: auto; /* Enables scrolling if content exceeds height */
            }
            .flex-grow {
                flex-grow: 1; /* Ensures label takes available space */
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('select-all');
                const contactCheckboxes = document.querySelectorAll('.contact-checkbox');

                // Toggle all checkboxes when "Select All" is clicked
                selectAllCheckbox.addEventListener('change', function() {
                    contactCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                // Update "Select All" state based on individual checkboxes
                contactCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(contactCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(contactCheckboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    });
                });

                // Initial state check
                const allCheckedInitially = Array.from(contactCheckboxes).every(cb => cb.checked);
                const someCheckedInitially = Array.from(contactCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allCheckedInitially;
                selectAllCheckbox.indeterminate = someCheckedInitially && !allCheckedInitially;
            });
        </script>
    @endpush
@endsection
