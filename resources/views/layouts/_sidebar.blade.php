<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Application</li>
            <li>
                <a href="{{ route('contacts.index') }}" class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Contacts</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:folder-outline" class="menu-icon"></iconify-icon>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="{{ route('smtp_settings.index') }}" class="{{ request()->routeIs('smtp_settings.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:settings-outline" class="menu-icon"></iconify-icon>
                    <span>SMTP Settings</span>
                </a>
            </li>
            @auth
                <li class="mt-5">
                    <form method="POST" action="{{ route('logout') }}" class="w-full mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:logout-outline" class="menu-icon text-red-500 text-2xl"></iconify-icon>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
                        <iconify-icon icon="solar:login-outline" class="menu-icon"></iconify-icon>
                        <span>Login</span>
                    </a>
                </li>
                @if (Route::has('register'))
                    <li>
                        <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">
                            <iconify-icon icon="solar:user-plus-outline" class="menu-icon"></iconify-icon>
                            <span>Register</span>
                        </a>
                    </li>
                @endif
            @endauth
        </ul>
    </div>
</aside>
