<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:block lg:w-64 lg:bg-white lg:border-r lg:border-gray-200 lg:pt-16 lg:pb-4 lg:overflow-y-auto lg:z-40">
    <!-- User Profile Section -->
    <div class="border-b border-gray-200 pb-2 px-4 pt-2">
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen"
                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition">
                <img class="w-8 h-8 rounded-full object-cover bg-gray-100"
                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                     alt="User avatar">
                <div class="flex-1 text-left">
                    <p class="font-medium text-gray-900 text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                class="absolute top-12 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">{{ __('Log Out') }}</button>
                </form>
            </div>
        </div>
    </div>

    <nav class="space-y-1 px-4 pt-2">
        <!-- Welcome -->
       

        <!-- Dashboard -->
        <x-sidebar-link 
            href="{{ route('dashboard') }}" 
            :active="request()->routeIs('dashboard')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l-4-2m0 0l-1 1m1-1l1 1m0-1l4-2m0 0l4 2m-4-2l1-1m-1 1l-1-1" />
            </svg>
            <span>{{ __('Dashboard') }}</span>
        </x-sidebar-link>

        <!-- Management Section -->
        <div class="pt-4 pb-1">
            <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Management') }}</h3>
        </div>

        <!-- Admin Panel -->
        @can('view admin panel')
        <x-sidebar-link 
            href="{{ route('admin.dashboard') }}" 
            :active="request()->routeIs('admin.*')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>{{ __('Admin Panel') }}</span>
        </x-sidebar-link>
        @endcan

        <!-- Roles -->
        <x-sidebar-link 
            href="{{ route('roles.index') }}" 
            :active="request()->routeIs('roles.*')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            <span>{{ __('Roles and Permissions') }}</span>
        </x-sidebar-link>

        <!-- Permissions (for future use) -->
        {{-- <x-sidebar-link 
            href="#" 
            :active="request()->routeIs('permissions.*')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ __('Permissions') }}</span>
        </x-sidebar-link> --}}

        <!-- Users -->
        <x-sidebar-link 
            href="{{ route('users.index') }}" 
            :active="request()->routeIs('users.*')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 1112 0v-2a6 6 0 00-12 0v2z" />
            </svg>
            <span>{{ __('Users') }}</span>
        </x-sidebar-link>

        <!-- Employees -->
        <x-sidebar-link 
            href="{{ route('employees.index') }}" 
            :active="request()->routeIs('employees.*')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            </svg>
            <span>{{ __('Karyawan') }}</span>
        </x-sidebar-link>

        <div class="pt-4 pb-1">
            <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Pages') }}</h3>
        </div>

         <x-sidebar-link 
            href="{{ url('/') }}" 
            :active="request()->routeIs('welcome')"
        >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l-4-2m0 0l-1 1m1-1l1 1m0-1l4-2m0 0l4 2m-4-2l1-1m-1 1l-1-1" />
            </svg>
            <span>{{ __('Home') }}</span>
        </x-sidebar-link>
    </nav>
</aside>
