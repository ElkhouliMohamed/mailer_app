@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                    <iconify-icon icon="solar:folder-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Categories
                </h1>
                <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Manage your contact categories</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:add-circle-outline" class="text-lg"></iconify-icon>
                Add Category
            </a>
        </div>

        <!-- Categories Table -->
        <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
            <div class="table-responsive">
                <table id="categories-table" class="table table-hover w-full">
                    <thead>
                    <tr class="bg-neutral-100 dark-bg-neutral-700">
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Name</th>
                        <th class="text-md fw-semibold text-black dark-text-white py-12 px-16">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-neutral-200 dark-border-neutral-600">
                            <td class="text-md text-black dark-text-white py-12 px-16">{{ $category->name }}</td>
                            <td class="py-12 px-16">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning-500 dark-btn-warning-400 py-8 px-16 radius-6 fw-medium text-sm d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:pen-outline" class="text-md"></iconify-icon>
                                        Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="delete-category-form d-inline" data-category-id="{{ $category->id }}">
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
                            <td colspan="2" class="text-center text-md text-secondary-light dark-text-neutral-300 py-16">No categories found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTable
                $('#categories-table').DataTable({
                    responsive: true,
                    pageLength: 10,
                    language: {
                        search: '_INPUT_',
                        searchPlaceholder: 'Search categories...'
                    },
                    dom: '<"d-flex justify-content-between align-items-center mb-4"lf>t<"d-flex justify-content-between mt-4"ip>',
                    drawCallback: function() {
                        // Re-attach SweetAlert to buttons after redraw
                        attachDeleteListeners();
                    }
                });

                // Function to attach SweetAlert to delete buttons
                function attachDeleteListeners() {
                    document.querySelectorAll('.delete-category-form').forEach(form => {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const categoryId = this.getAttribute('data-category-id');
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'This category will be deleted permanently!',
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

                // Initial attachment of delete listeners
                attachDeleteListeners();

                // Success alert from session
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
