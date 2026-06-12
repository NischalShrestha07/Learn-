@props(['href'])

<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition']) }}>
    {{ $slot }}
</a>
