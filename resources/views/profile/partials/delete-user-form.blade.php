<section class="space-y-6">
    <header>
        <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ __('Delete Account') }}</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('Are you sure?') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}</p>

            <div>
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="block w-3/4" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
