@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-imperial-red text-sm font-medium leading-5 text-white-smoke focus:outline-none focus:border-imperial-red transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-timberwolf hover:text-white-smoke hover:border-silver focus:outline-none focus:text-white-smoke focus:border-silver transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
