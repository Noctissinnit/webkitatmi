<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Role') }}
        </h2>
    </x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left w-4 h-4"></i>
            {{ __('Back to Roles') }}
        </a>
    </div>

    <!-- Form Container -->
    <div class="table-container" style="max-width: 600px;">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <!-- Role Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Role Name') }}
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}"
                    placeholder="e.g., Editor, Moderator"
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
                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="permission-{{ $permission->id }}" 
                                name="permissions[]" 
                                value="{{ $permission->id }}"
                                class="h-4 w-4 text-gray-900 rounded border-gray-300 mt-0.5"
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
            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                <a 
                    href="{{ route('roles.index') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    <i class="fas fa-times w-4 h-4"></i>
                    {{ __('Cancel') }}
                </a>
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors"
                >
                    <i class="fas fa-check w-4 h-4"></i>
                    {{ __('Create Role') }}
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/employees-table.css') }}">
    @endpush
</x-app-layout>
