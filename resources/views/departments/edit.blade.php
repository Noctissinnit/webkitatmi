<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Department') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Edit Department</h1>

    <form action="{{ route('departments.update', $department) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold mb-2">Nama Department</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                class="w-full border border-gray-300 rounded px-3 py-2 @error('name') border-red-500 @enderror"
                value="{{ old('name', $department->name) }}"
                required
            >
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold mb-2">Deskripsi (Opsional)</label>
            <textarea 
                name="description" 
                id="description" 
                class="w-full border border-gray-300 rounded px-3 py-2 h-24 @error('description') border-red-500 @enderror"
            >{{ old('description', $department->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Perbarui
            </button>
            <a href="{{ route('departments.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                Batal
            </a>
        </div>
    </form>
</div>
</x-app-layout>
