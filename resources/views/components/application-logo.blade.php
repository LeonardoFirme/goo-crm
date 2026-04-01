<!-- resources/views/components/application-logo.blade.php -->
@props(['class' => 'w-10 h-10'])

<div {{ $attributes->merge(['class' => $class]) }}>
    <img src="{{ asset('logo.png') }}"
         alt="{{ config('app.name') }}"
         class="w-full h-full object-cover rounded-lg shadow-sm">
</div>