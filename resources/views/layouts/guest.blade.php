<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 selection:bg-blue-600 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            {{-- Logo ou Identificador Minimalista --}}
            <div class="mb-8">
                <a href="/">
                    <x-application-logo class="w-12 h-12 fill-current text-gray-900" />
                </a>
            </div>

            {{-- Container do Formulário --}}
            <div class="w-full sm:max-w-md px-10 py-12 bg-white border border-gray-200/50 shadow-sm rounded-2xl overflow-hidden">
                {{ $slot }}
            </div>

            {{-- Footer Auxiliar --}}
            <div class="mt-8 text-center">
                <p class="text-[11px] font-semibold text-gray-400 tracking-tight">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Segurança e Privacidade.
                </p>
            </div>
        </div>
    </body>
</html>