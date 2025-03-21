@extends('layouts.app')

@section('title', 'Edit Contact')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:users-group-rounded-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Edit Contact
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Update contact details</p>
            </div>
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark-btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                Back to Contacts
            </a>
        </div>

        <!-- Edit Form -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <form method="POST" action="{{ route('contacts.update', $contact) }}" id="editContactForm">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label for="first_name" class="form-label text-lg fw-medium text-black dark-text-white">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:user-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="first_name" id="first_name" class="form-control radius-8 text-lg py-12" value="{{ old('first_name', $contact->first_name) }}" required autofocus>
                        @error('first_name')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="last_name" class="form-label text-lg fw-medium text-black dark-text-white">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:user-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="last_name" id="last_name" class="form-control radius-8 text-lg py-12" value="{{ old('last_name', $contact->last_name) }}">
                        @error('last_name')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="email" class="form-label text-lg fw-medium text-black dark-text-white">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:mailbox-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="email" name="email" id="email" class="form-control radius-8 text-lg py-12" value="{{ old('email', $contact->email) }}" required>
                        @error('email')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="category_id" class="form-label text-lg fw-medium text-black dark-text-white">Category</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:folder-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <select name="category_id" id="category_id" class="form-select radius-8 text-lg py-12">
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $contact->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="phone" class="form-label text-lg fw-medium text-black dark-text-white">Phone</label>
                    <div class="input-group">
                        <span class="input-group-text bg-neutral-100 dark-bg-neutral-700">
                            <iconify-icon icon="solar:phone-outline" class="text-primary-light dark-text-neutral-300 text-xl"></iconify-icon>
                        </span>
                        <input type="text" name="phone" id="phone" class="form-control radius-8 text-lg py-12" value="{{ old('phone', $contact->phone) }}">
                        @error('phone')
                        <span class="text-danger dark-text-danger-300 text-sm mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:check-circle-outline" class="text-lg"></iconify-icon>
                        Update Contact
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
                const form = document.getElementById('editContactForm');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to update this contact?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update it!',
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
