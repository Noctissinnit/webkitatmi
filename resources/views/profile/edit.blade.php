<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="table-container" style="max-width: 600px;">
            <div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="table-container" style="max-width: 600px;">
            <div>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="table-container" style="max-width: 600px;">
            <div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/employees-table.css') }}">
    @endpush
</x-app-layout>
