@props(['active', 'icon' => null])

@php
$classes = ($active ?? false)
    ? 'sidebar-link-active'
    : 'sidebar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span>{{ $slot }}</span>
</a>
