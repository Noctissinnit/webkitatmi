<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users Management') }}
            </h2>
            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                {{ __('Create User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($user->roles->count() > 0)
                                            <div class="flex gap-1 flex-wrap">
                                                @foreach($user->roles as $role)
                                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-full">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">No roles</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        @if(auth()->user()->id !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                @forelse($users as $user)
                    <div class="bg-white rounded-lg shadow-sm p-4 space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Name</p>
                            <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Email</p>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Roles</p>
                            @if($user->roles->count() > 0)
                                <div class="flex gap-1 flex-wrap mt-1">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-full">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 text-sm">No roles</p>
                            @endif
                        </div>
                        <div class="pt-3 border-t flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="flex-1 text-center px-3 py-2 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700">Edit</a>
                            @if(auth()->user()->id !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                        No users found.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
