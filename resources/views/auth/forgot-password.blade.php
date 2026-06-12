<x-guest-layout>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="you@school.edu" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <x-primary-button class="w-full justify-center">{{ __('Send reset link') }}</x-primary-button>

        <p class="text-center text-sm">
            <a href="{{ route('login') }}" class="link">{{ __('Back to login') }}</a>
        </p>
    </form>
</x-guest-layout>
