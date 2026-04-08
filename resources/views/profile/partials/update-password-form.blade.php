<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="mb-6">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm" />
        </div>

        <div class="mb-6">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="update_password_password" name="password" type="password" class="block w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm" />
        </div>

        <div class="mb-6">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm" />
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                <i class="fas fa-check w-4 h-4"></i>
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
