<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@school.edu" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-slate-200 dark:border-slate-700 text-cyan-600 focus:ring-cyan-500" name="remember">
                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="link" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center">{{ __('Log in') }}</x-primary-button>

        <p class="text-center text-sm text-slate-500 dark:text-slate-400">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="link">Sign up</a>
        </p>
    </form>
</x-guest-layout>
