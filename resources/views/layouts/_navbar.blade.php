<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <!-- Left Side: Sidebar Toggle and Search -->
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <!-- Sidebar Toggle (Desktop) -->
                <button type="button" class="sidebar-toggle" id="sidebarToggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active hidden"></iconify-icon>
                </button>
                <!-- Sidebar Toggle (Mobile) -->
                <button type="button" class="sidebar-mobile-toggle" id="sidebarMobileToggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl"></iconify-icon>
                </button>
                <!-- Search Form -->
                <form class="navbar-search" method="GET" action="{{ route('contacts.index') }}">
                    <input type="text" name="search" placeholder="Search Contacts" value="{{ request('search') }}">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
        </div>

        <!-- Right Side: Theme Toggle, Messages, Profile -->
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <!-- Theme Toggle -->
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center">
                    <iconify-icon icon="solar:moon-linear" class="icon text-xl dark-hidden"></iconify-icon>
                    <iconify-icon icon="solar:sun-linear" class="icon text-xl hidden light-hidden"></iconify-icon>
                </button>

                <!-- Message Dropdown -->
                <div class="dropdown">
                    <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                        <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                        @php
                            $unreadMessages = App\Models\EmailLog::where('status', 'sent')->count(); // Adjust logic as needed
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge bg-danger position-absolute top-0 end-0 w-16-px h-16-px rounded-circle d-flex justify-content-center align-items-center">{{ $unreadMessages > 9 ? '9+' : $unreadMessages }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <h6 class="text-lg text-primary-light fw-semibold mb-0">Messages</h6>
                            <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">{{ $unreadMessages }}</span>
                        </div>
                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                            <!-- Dynamic Message List (Example) -->
                            @for($i = 0; $i < min($unreadMessages, 5); $i++)
                                <a href="{{ route('email-logs.index') }}" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-' . ($i + 3) . '.png') }}" alt="">
                                            <span class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Message #{{ $i + 1 }}</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">Email log entry...</p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">{{ now()->subMinutes(rand(1, 60))->format('h:i A') }}</span>
                                        <span class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">{{ rand(0, 8) }}</span>
                                    </div>
                                </a>
                            @endfor
                        </div>
                        <div class="text-center py-12 px-16">
                            <a href="{{ route('email-logs.index') }}" class="text-primary-600 fw-semibold text-md">See All Messages</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/images/user.png') }}" alt="User" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth()->user()->name ?? 'Guest' }}</h6>
                                <span class="text-secondary-light fw-medium text-sm">User</span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            @auth
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('dashboard') }}">
                                        <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('email-logs.index') }}">
                                        <iconify-icon icon="tabler:message-check" class="icon text-xl"></iconify-icon> Email Logs
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('smtp_settings.index') }}">
                                        <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Settings
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3 w-full text-left">
                                            <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('login') }}">
                                        <iconify-icon icon="solar:login-outline" class="icon text-xl"></iconify-icon> Login
                                    </a>
                                </li>
                                @if (Route::has('register'))
                                    <li>
                                        <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('register') }}">
                                            <iconify-icon icon="solar:user-plus-outline" class="icon text-xl"></iconify-icon> Register
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Sidebar Toggle -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar'); // Adjust selector based on your sidebar class
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarMobileToggle = document.getElementById('sidebarMobileToggle');
            const toggleIcons = sidebarToggle.querySelectorAll('.icon');

            // Toggle sidebar visibility for desktop
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                toggleIcons.forEach(icon => icon.classList.toggle('hidden'));
            });

            // Toggle sidebar visibility for mobile
            sidebarMobileToggle.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });

            // Theme toggle icon switch
            const themeToggle = document.querySelector('[data-theme-toggle]');
            const moonIcon = themeToggle.querySelector('.dark-hidden');
            const sunIcon = themeToggle.querySelector('.light-hidden');
            themeToggle.addEventListener('click', function () {
                moonIcon.classList.toggle('hidden');
                sunIcon.classList.toggle('hidden');
            });
        });
    </script>
@endpush
