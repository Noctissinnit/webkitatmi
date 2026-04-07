@props(['active' => false])

<a
    {{ $attributes->merge(['href' => '#']) }}
    class="group relative flex w-full items-center space-x-3 rounded-lg px-4 py-2 text-sm transition-colors duration-150
        {{ $active 
            ? 'bg-indigo-50 text-indigo-600 font-medium' 
            : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}"
>
    {{ $slot }}
</a>
