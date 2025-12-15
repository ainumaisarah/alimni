@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 pb-2 border-b-2 border-[#7c3333] text-sm font-bold leading-4 text-[#7c3333] focus:outline-none focus:border-[#7c3333] transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 pb-2 border-b-2 border-transparent text-sm font-bold leading-4 text-[#7c3333] hover:text-[#7c3333] hover:border-[#7c3333] focus:outline-none focus:text-[#7c3333] focus:border-[#7c3333] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
