<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Karyawan') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $employee->nama) }}" required
                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-indigo-600 focus:outline-none @error('nama') border-red-500 @enderror">
                @error('nama')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required
                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-indigo-600 focus:outline-none @error('email') border-red-500 @enderror">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Departemen -->
            <div>
                <label for="departemen" class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                <input type="text" id="departemen" name="departemen" value="{{ old('departemen', $employee->departemen) }}" required
                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-indigo-600 focus:outline-none @error('departemen') border-red-500 @enderror">
                @error('departemen')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jabatan -->
            <div>
                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" required
                    class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:border-indigo-600 focus:outline-none @error('jabatan') border-red-500 @enderror">
                @error('jabatan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <a href="{{ route('employees.index') }}" class="flex-1 px-4 py-2 text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
