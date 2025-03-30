@extends('layouts.app')

@section('title', 'Import/Export Contacts')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark:text-primary-300">
                    <iconify-icon icon="solar:import-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Import/Export Contacts
                </h1>
                <p class="text-md text-secondary-light dark:text-neutral-300 mt-2">Manage your contact imports and exports</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark:btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-outline" class="text-lg"></iconify-icon>
                    View Contacts
                </a>
            </div>
        </div>

        <!-- Import/Export Section -->
        <div class="card radius-12 shadow-xl bg-white dark:bg-neutral-800 p-5 mb-6">
            <!-- Import Form -->
            <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <h3 class="text-md fw-semibold text-black dark:text-white mb-4">
                    <iconify-icon icon="solar:upload-minimalistic-outline" class="text-lg align-middle mr-2"></iconify-icon>
                    Import Contacts
                </h3>
                <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                    <!-- Category Selection -->
                    <div class="flex-grow">
                        <label for="category_id" class="text-md fw-semibold text-black dark:text-white mb-2 block">Select Category</label>
                        <select name="category_id" id="category_id" class="form-select radius-8 py-12 px-16 text-md w-full">
                            <option value="">-- Select a Category --</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="text-danger-600 dark:text-danger-400 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="flex-grow">
                        <label for="file" class="text-md fw-semibold text-black dark:text-white mb-2 block">Upload CSV/Excel File</label>
                        <input type="file" name="file" id="file" class="form-control radius-8 py-12 px-16 text-md w-full" accept=".csv,.xlsx,.xls" required>
                        @error('file')
                        <span class="text-danger-600 dark:text-danger-400 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary-600 dark:btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:import-outline" class="text-lg"></iconify-icon>
                        Import Contacts
                    </button>
                    <a href="{{ route('contacts.sample') }}" class="btn btn-outline-neutral-600 dark:btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:download-minimalistic-outline" class="text-lg"></iconify-icon>
                        Download Sample
                    </a>
                </div>
            </form>

            <!-- Export Form -->
            <form action="{{ route('contacts.export') }}" method="GET" class="mt-5">
                <h3 class="text-md fw-semibold text-black dark:text-white mb-4">
                    <iconify-icon icon="solar:export-outline" class="text-lg align-middle mr-2"></iconify-icon>
                    Export Contacts
                </h3>
                <div class="d-flex gap-3 align-items-center flex-wrap mb-4">
                    <!-- Category Selection for Export -->
                    <div class="flex-grow">
                        <label for="export_category_id" class="text-md fw-semibold text-black dark:text-white mb-2 block">Select Category</label>
                        <select name="category_id" id="export_category_id" class="form-select radius-8 py-12 px-16 text-md w-full">
                            <option value="">-- All Categories --</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success-600 dark:btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:export-outline" class="text-lg"></iconify-icon>
                    Export Contacts
                </button>
            </form>
        </div>

        <!-- Contact List -->
        <div class="card radius-12 shadow-xl bg-white dark:bg-neutral-800 p-5">
            <h3 class="text-md fw-semibold text-black dark:text-white mb-4">
                <iconify-icon icon="solar:users-group-rounded-outline" class="text-lg align-middle mr-2"></iconify-icon>
                Contact List
            </h3>
            <!-- Filters and Search -->
            <form method="GET" action="{{ route('contacts.import-export') }}" class="d-flex gap-3 align-items-center flex-wrap mb-4" id="searchForm">
                <input type="text" name="first_name" class="form-control radius-8 py-12 px-16 text-md" placeholder="First Name" value="{{ request('first_name') }}">
                <input type="text" name="last_name" class="form-control radius-8 py-12 px-16 text-md" placeholder="Last Name" value="{{ request('last_name') }}">
                <input type="email" name="email" class="form-control radius-8 py-12 px-16 text-md" placeholder="Email" value="{{ request('email') }}">
                <button type="submit" class="btn btn-primary-600 dark:btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md" id="searchButton">
                    Filter
                </button>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="bg-neutral-100 dark:bg-neutral-700">
                        <th class="text-md fw-semibold text-black dark:text-white py-12 px-16 text-left">First Name</th>
                        <th class="text-md fw-semibold text-black dark:text-white py-12 px-16 text-left">Last Name</th>
                        <th class="text-md fw-semibold text-black dark:text-white py-12 px-16 text-left">Email</th>
                        <th class="text-md fw-semibold text-black dark:text-white py-12 px-16 text-left">Category</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($contacts as $contact)
                        <tr class="border-b border-neutral-200 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-600">
                            <td class="text-md text-black dark:text-white py-12 px-16">{{ $contact->first_name }}</td>
                            <td class="text-md text-black dark:text-white py-12 px-16">{{ $contact->last_name }}</td>
                            <td class="text-md text-black dark:text-white py-12 px-16">{{ $contact->email }}</td>
                            <td class="text-md text-black dark:text-white py-12 px-16">{{ $contact->category->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-md text-secondary-light dark:text-neutral-300 py-12 px-16 text-center">No contacts found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>

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
    </div>
@endsection
