@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid p-4">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl fw-semibold text-primary-600 dark-text-primary-300">
                <iconify-icon icon="solar:widget-outline" class="text-3xl align-middle mr-2"></iconify-icon>
                Admin Dashboard
            </h1>
            <p class="text-md text-secondary-light dark-text-neutral-300 mt-2">Overview of your emailing system</p>
        </div>

        <!-- Stats Row -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-primary-100 dark-bg-primary-900 text-primary-600 dark-text-primary-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:users-group-rounded-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Total Contacts</h5>
                            <p class="text-2xl fw-bold text-primary-600 dark-text-primary-300 mb-0">{{ $totalContacts }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-success-100 dark-bg-success-900 text-success-600 dark-text-success-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:folder-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Total Categories</h5>
                            <p class="text-2xl fw-bold text-success-600 dark-text-success-300 mb-0">{{ $totalCategories }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-info-100 dark-bg-info-900 text-info-600 dark-text-info-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:mailbox-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Emails Sent</h5>
                            <p class="text-2xl fw-bold text-info-600 dark-text-info-300 mb-0">{{ $totalEmailsSent }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-danger-100 dark-bg-danger-900 text-danger-600 dark-text-danger-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Trashed Contacts</h5>
                            <p class="text-2xl fw-bold text-danger-600 dark-text-danger-300 mb-0">{{ $trashedContacts }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- New Email Stats -->
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-warning-100 dark-bg-warning-900 text-warning-600 dark-text-warning-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Emails Pending</h5>
                            <p class="text-2xl fw-bold text-warning-600 dark-text-warning-300 mb-0">{{ $totalEmailsPending }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5 transition-all hover-shadow-2xl">
                    <div class="d-flex align-items-center gap-3">
                        <span class="w-50-px h-50-px bg-danger-100 dark-bg-danger-900 text-danger-600 dark-text-danger-300 rounded-circle d-flex justify-content-center align-items-center">
                            <iconify-icon icon="solar:close-circle-outline" class="text-2xl"></iconify-icon>
                        </span>
                        <div>
                            <h5 class="text-lg fw-semibold text-black dark-text-white mb-1">Emails Failed</h5>
                            <p class="text-2xl fw-bold text-danger-600 dark-text-danger-300 mb-0">{{ $totalEmailsFailed }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and SMTP Row -->
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
                    <h5 class="text-xl fw-semibold text-black dark-text-white mb-4">
                        <iconify-icon icon="solar:chart-square-outline" class="text-xl align-middle mr-2"></iconify-icon>
                        Contacts by Category
                    </h5>
                    <div id="category-chart" class="h-400-px"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
                    <h5 class="text-xl fw-semibold text-black dark-text-white mb-4">
                        <iconify-icon icon="solar:pie-chart-outline" class="text-xl align-middle mr-2"></iconify-icon>
                        Contact Distribution
                    </h5>
                    <div id="pie-chart" class="h-400-px"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
                    <h5 class="text-xl fw-semibold text-black dark-text-white mb-4">
                        <iconify-icon icon="solar:donut-chart-outline" class="text-xl align-middle mr-2"></iconify-icon>
                        Email Status Distribution
                    </h5>
                    <div id="email-status-chart" class="h-400-px"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card radius-12 shadow-xl bg-white dark-bg-neutral-800 p-5">
                    <h5 class="text-xl fw-semibold text-black dark-text-white mb-4">
                        <iconify-icon icon="solar:settings-outline" class="text-xl align-middle mr-2"></iconify-icon>
                        SMTP Settings
                    </h5>
                    <p class="text-lg text-secondary-light dark-text-neutral-300 mb-4">Total: <span class="fw-bold text-primary-600 dark-text-primary-300">{{ $smtpSettingsCount }}</span></p>
                    <a href="{{ route('smtp_settings.index') }}" class="btn btn-primary-600 dark-btn-primary-500 py-12 px-24 radius-8 fw-semibold text-md d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:settings-line-duotone" class="text-lg"></iconify-icon>
                        Manage SMTP
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Bar Chart (Contacts by Category)
                var barOptions = {
                    chart: { type: 'bar', height: 350, toolbar: { show: false }, background: 'transparent' },
                    series: [{ name: 'Contacts', data: [@foreach($contactsByCategory as $category){{ $category->contacts_count }},@endforeach] }],
                    xaxis: {
                        categories: [@foreach($contactsByCategory as $category)'{{ $category->name }}', @endforeach],
                        labels: { style: { colors: document.documentElement.getAttribute('data-theme') === 'dark' ? '#d1d5db' : '#6b7280' } }
                    },
                    yaxis: { labels: { style: { colors: document.documentElement.getAttribute('data-theme') === 'dark' ? '#d1d5db' : '#6b7280' } } },
                    plotOptions: { bar: { borderRadius: 4, horizontal: false } },
                    colors: ['#3b82f6'],
                    dataLabels: { enabled: false },
                    theme: { mode: document.documentElement.getAttribute('data-theme') || 'light' }
                };
                var barChart = new ApexCharts(document.querySelector("#category-chart"), barOptions);
                barChart.render();

                // Pie Chart (Contact Distribution)
                var pieOptions = {
                    chart: { type: 'pie', height: 350, background: 'transparent' },
                    series: [@foreach($contactsByCategory as $category){{ $category->contacts_count }},@endforeach],
                    labels: [@foreach($contactsByCategory as $category)'{{ $category->name }}', @endforeach],
                    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    dataLabels: { enabled: true, style: { colors: ['#fff'] } },
                    legend: { position: 'bottom', labels: { colors: document.documentElement.getAttribute('data-theme') === 'dark' ? '#d1d5db' : '#6b7280' } },
                    responsive: [{ breakpoint: 480, options: { chart: { width: 300 }, legend: { position: 'bottom' } } }],
                    theme: { mode: document.documentElement.getAttribute('data-theme') || 'light' }
                };
                var pieChart = new ApexCharts(document.querySelector("#pie-chart"), pieOptions);
                pieChart.render();

                // Donut Chart (Email Status Distribution)
                var emailStatusOptions = {
                    chart: { type: 'donut', height: 350, background: 'transparent' },
                    series: [@foreach($emailStatuses as $status => $count){{ $count }},@endforeach],
                    labels: [@foreach($emailStatuses as $status => $count)'{{ $status }}', @endforeach],
                    colors: ['#10b981', '#ef4444', '#f59e0b'], // Green for sent, Red for failed, Yellow for pending
                    dataLabels: { enabled: true, style: { colors: ['#fff'] } },
                    legend: { position: 'bottom', labels: { colors: document.documentElement.getAttribute('data-theme') === 'dark' ? '#d1d5db' : '#6b7280' } },
                    responsive: [{ breakpoint: 480, options: { chart: { width: 300 }, legend: { position: 'bottom' } } }],
                    theme: { mode: document.documentElement.getAttribute('data-theme') || 'light' }
                };
                var emailStatusChart = new ApexCharts(document.querySelector("#email-status-chart"), emailStatusOptions);
                emailStatusChart.render();

                // Theme Toggle Updates
                document.querySelector('[data-theme-toggle]')?.addEventListener('click', () => {
                    const theme = document.documentElement.getAttribute('data-theme');
                    barChart.updateOptions({
                        theme: { mode: theme },
                        xaxis: { labels: { style: { colors: theme === 'dark' ? '#d1d5db' : '#6b7280' } } },
                        yaxis: { labels: { style: { colors: theme === 'dark' ? '#d1d5db' : '#6b7280' } } }
                    });
                    pieChart.updateOptions({
                        theme: { mode: theme },
                        legend: { labels: { colors: theme === 'dark' ? '#d1d5db' : '#6b7280' } }
                    });
                    emailStatusChart.updateOptions({
                        theme: { mode: theme },
                        legend: { labels: { colors: theme === 'dark' ? '#d1d5db' : '#6b7280' } }
                    });
                });
            });
        </script>
    @endpush
@endsection
