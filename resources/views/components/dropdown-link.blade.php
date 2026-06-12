@props(['href'])

<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition']) }}>
    {{ $slot }}
</a>
