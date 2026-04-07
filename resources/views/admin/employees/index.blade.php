<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👥 {{ __('Daftar Karyawan') }}
            </h2>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                + Tambah Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-700 text-sm font-medium">✓ {{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-900">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Posisi</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Departemen</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($employees as $employee)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($employee->photo)
                                                <img src="{{ Storage::url($employee->photo) }}" alt="{{ $employee->name }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-semibold text-indigo-700">
                                                    {{ substr($employee->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900">{{ $employee->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $employee->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $employee->position ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $employee->department ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($employee->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="text-4xl mb-3">📭</div>
                                            <p class="text-gray-500 font-medium">Belum ada karyawan</p>
                                            <p class="text-gray-400 text-sm mt-1">Mulai dengan <a href="{{ route('admin.employees.create') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">membuat karyawan baru</a></p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                    <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
