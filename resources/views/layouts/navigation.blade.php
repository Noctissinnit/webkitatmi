<nav x-data="{ mobileMenuOpen: false }" class="fixed w-full top-0 h-16 bg-white border-b border-gray-200 z-50 lg:pr-64">
    <div class="h-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-full">
            <!-- Logo Left -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                </a>
            </div>

            <!-- Mobile: Menu Button Right -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                <svg class="w-6 h-6" :class="{'hidden': mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg class="w-6 h-6" :class="{'hidden': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="sm:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg text-sm">{{ __('Dashboard') }}</a>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg text-sm">{{ __('Profile') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg text-sm">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
</nav>
