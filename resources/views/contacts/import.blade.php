@extends('layouts.app')

@section('title', 'Import Contacts')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="text-3xl fw-semibold text-primary-600 dark:text-primary-300">
                    <iconify-icon icon="solar:import-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                    Import Contacts
                </h1>
                <p class="text-md text-secondary-light dark:text-neutral-300 mt-2">Upload an Excel or CSV file to add multiple contacts</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('contacts.index') }}" class="btn btn-outline-neutral-600 dark:btn-outline-neutral-400 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                    Back to Contacts
                </a>
            </div>
        </div>

        <!-- Import Form -->
        <div class="card radius-12 shadow-xl bg-white dark:bg-neutral-800 p-5">
            <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="text-md fw-semibold text-black dark:text-white">Upload File</label>
                    <input type="file" name="file" id="file" class="form-control radius-8 py-12 px-16 text-md" accept=".csv, .xlsx, .xls" required>
                    <p class="text-sm text-secondary-light dark:text-neutral-400 mt-2">
                        Supported formats: CSV, Excel (.xlsx, .xls). Required columns: first_name, last_name, email (optional: phone, company, category_id).
                    </p>
                </div>

                <button type="submit" class="btn btn-success-600 dark:btn-success-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:upload-outline" class="text-lg"></iconify-icon>
                    Import Contacts
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
        </div>

        <!-- Download Sample File -->
        <div class="mt-4">
            <a href="{{ route('contacts.sample') }}" class="btn btn-outline-primary-600 dark:btn-outline-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                <iconify-icon icon="solar:download-outline" class="text-lg"></iconify-icon>
                Download Sample CSV
            </a>
        </div>
    </div>
@endsection
