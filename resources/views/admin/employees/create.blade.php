<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Karyawan') }}
        </h2>
    </x-slot>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left w-4 h-4"></i>
            {{ __('Back to Employees') }}
        </a>
    </div>

    <!-- Form Container -->
    <div class="table-container" style="max-width: 600px;">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Photo Upload -->
            <div class="mb-6">
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('Employee Photo') }}
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

            <!-- Nama -->
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('nama') border-red-500 @enderror"
                >
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Departemen -->
            <div class="mb-6">
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <select 
                    id="department_id" 
                    name="department_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('department_id') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan -->
            <div class="mb-6">
                <label for="position_id" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                <select 
                    id="position_id" 
                    name="position_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('position_id') border-red-500 @enderror"
                >
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}
                        </option>
                    @endforeach
                </select>
                @error('position_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 justify-end pt-6 border-t border-gray-200">
                <a 
                    href="{{ route('employees.index') }}" 
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
                    {{ __('Simpan') }}
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
