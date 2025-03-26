<aside class="sidebar">
    <button type="button" class="sidebar-close-btn" aria-label="Close sidebar">
        <iconify-icon icon="radix-icons:cross-2" width="24" height="24"></iconify-icon>
    </button>

    <div class="sidebar-logo-container">
        <a href="{{ route('dashboard') }}" class="sidebar-logo" aria-label="Dashboard">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Site Logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="Site Logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Site Logo" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   aria-label="Dashboard">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Application</li>

            <li>
                <a href="{{ route('contacts.index') }}"
                   class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}"
                   aria-label="Contacts">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Contacts</span>
                </a>
            </li>

            <li>
                <a href="{{ route('categories.index') }}"
                   class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"
                   aria-label="Categories">
                    <iconify-icon icon="solar:folder-outline" class="menu-icon"></iconify-icon>
                    <span>Categories</span>
                </a>
            </li>

            <li>
                <a href="{{ route('smtp_settings.index') }}"
                   class="{{ request()->routeIs('smtp_settings.*') ? 'active' : '' }}"
                   aria-label="SMTP Settings">
                    <iconify-icon icon="solar:settings-outline" class="menu-icon"></iconify-icon>
                    <span>SMTP Settings</span>
                </a>
            </li>

            <li>
                <a href="{{ route('email-logs.index') }}"
                   class="{{ request()->routeIs('email-logs.*') ? 'active' : '' }}"
                   aria-label="Email Logs">
                    <iconify-icon icon="solar:letter-outline" class="menu-icon"></iconify-icon>
                    <span>Email Logs</span>
                </a>
            </li>

            @auth
                <li class="mt-5">
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-4 ">
                        @csrf
                        <button type="submit"
                                id="logout-btnn"
                                class="w-full text-left flex gap-2 items-center hover:text-red-600"
                                aria-label="Logout">
                            <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                            Logout
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}"
                       class="{{ request()->routeIs('login') ? 'active' : '' }}"
                       aria-label="Login">
                        <iconify-icon icon="solar:login-outline" class="menu-icon"></iconify-icon>
                        <span>Login</span>
                    </a>
                </li>
                @if (Route::has('register'))
                    <li>
                        <a href="{{ route('register') }}"
                           class="{{ request()->routeIs('register') ? 'active' : '' }}"
                           aria-label="Register">
                            <iconify-icon icon="solar:user-plus-outline" class="menu-icon"></iconify-icon>
                            <span>Register</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </div>
</aside>
