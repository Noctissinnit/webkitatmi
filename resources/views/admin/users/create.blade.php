<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left w-4 h-4"></i>
            {{ __('Back to Users') }}
        </a>
    </div>

    <!-- Form Container -->
    <div class="table-container" style="max-width: 600px;">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Photo Upload -->
            <div class="mb-6">
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('User Photo') }}
                </label>
                <div class="flex gap-4 items-start">
                    <div class="flex-1">
                        <input 
                            type="file" 
                            id="photo" 
                            name="photo" 
                            accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        >
                        <p class="mt-1 text-xs text-gray-500">{{ __('JPG, PNG up to 2MB') }}</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="photoPreview" class="hidden">
                        <img id="previewImg" src="" alt="Preview" class="w-20 h-20 rounded-lg object-cover border border-gray-300">
                    </div>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Name') }}
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autocomplete="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Full name"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Email') }}
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="user@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Password') }}
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password') border-red-500 @enderror"
                    placeholder="Minimum 8 characters"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Confirm Password') }}
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('password_confirmation') border-red-500 @enderror"
                    placeholder="Repeat password"
                >
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Roles -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    {{ __('Assign Roles') }}
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($roles as $role)
                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="role_{{ $role->id }}" 
                                name="roles[]" 
                                value="{{ $role->id }}"
                                class="h-4 w-4 text-gray-900 rounded border-gray-300 mt-0.5"
                            >
                            <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700">
                                {{ ucfirst($role->name) }}
                            </label>
                        </div>
                    @empty
                        <p class="text-gray-500">{{ __('No roles available') }}</p>
                    @endforelse
                </div>
                @error('roles')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                <a 
                    href="{{ route('users.index') }}" 
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
                    {{ __('Create User') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');
        const previewImg = document.getElementById('previewImg');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        photoPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    photoPreview.classList.add('hidden');
                }
            });
        }
    </script>
    @endpush

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/employees-table.css') }}">
    @endpush
</x-app-layout>
