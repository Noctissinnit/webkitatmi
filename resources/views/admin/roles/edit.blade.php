<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <!-- Role Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Role Name') }}
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                                value="{{ old('name', $role->name) }}"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                {{ __('Permissions') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="permission-{{ $permission->id }}" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        >
                                        <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-500">{{ __('No permissions available') }}</p>
                                @endforelse
                            </div>
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 justify-end">
                            <a 
                                href="{{ route('roles.index') }}" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                            >
                                {{ __('Cancel') }}
                            </a>
                            <button 
                                type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                            >
                                {{ __('Update Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
