<x-guest-layout>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
        {{ __('This is a secure area. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <x-primary-button class="w-full justify-center">{{ __('Confirm') }}</x-primary-button>
    </form>
</x-guest-layout>
