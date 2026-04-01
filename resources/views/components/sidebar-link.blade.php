@props(['active', 'icon'])

@php
    $classes = ($active ?? false)
        ? 'flex items-center gap-3 px-4 py-3 text-base font-bold text-white bg-blue-600 rounded-lg shadow-lg shadow-blue-900/20 transition-all'
        : 'flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} w-5 text-center"></i>
    <span>{{ $slot }}</span>
</a>