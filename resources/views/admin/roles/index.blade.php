<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles Management') }}
            </h2>
            <a href="{{ route('roles.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150 text-sm font-medium">
                {{ __('Create Role') }}
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Success Message -->
            @if (session('success'))
                <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Roles Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                @if ($roles->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Permissions') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Created') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($roles as $role)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $role->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($role->permissions as $permission)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $permission->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400 italic">{{ __('No permissions') }}</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $role->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                            <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ __('Edit') }}
                                            </a>
                                            @if ($role->name !== 'admin')
                                                <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden divide-y">
                        @foreach ($roles as $role)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="font-semibold text-gray-900">{{ $role->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $role->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($role->permissions as $permission)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 italic text-sm">{{ __('No permissions') }}</span>
                                    @endforelse
                                </div>
                                <div class="flex gap-3 pt-2 border-t">
                                    <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        {{ __('Edit') }}
                                    </a>
                                    @if ($role->name !== 'admin')
                                        <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="bg-gray-50 px-4 py-3 border-t">
                        {{ $roles->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        {{ __('No roles found. Create one!') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
