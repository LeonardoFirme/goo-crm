<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Título na Guia do Navegador --}}
    <title>{{ $title }} | {{ config('app.name', 'ERP Simples') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-green-100/20 via-blue-100/20 to-red-100/20"
    x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="flex flex-col flex-1 w-full overflow-y-auto overflow-x-hidden">

            {{-- Top Header --}}
            <header
                class="h-16 bg-white border-b border-gray-200/50 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-sm font-bold text-gray-400 tracking-widest">
                        {{ config('app.name') }} <span class="mx-2 text-gray-200">|</span> ERP Simples
                    </h1>
                </div>

                <div class="flex items-center gap-6">
                    <div
                        class="hidden md:flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Status: Online
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-[11px] font-bold text-rose-500 hover:text-rose-700 transition-colors uppercase tracking-widest">
                            <i class="fas fa-power-off mr-1"></i> Sair
                        </button>
                    </form>
                </div>
            </header>

            {{-- Conteúdo das Páginas --}}
            <main class="p-6 md:p-10 flex-grow">

                {{-- Cabeçalho Dinâmico da Página --}}
                <div class="mb-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tighter uppercase">
                                {{ $title }}
                            </h2>
                            @if($subtitle)
                                <p class="text-sm text-gray-500 font-medium mt-1">
                                    {{ $subtitle }}
                                </p>
                            @endif
                        </div>

                        {{-- Espaço opcional para o Slot de Header (Botões de Ação) --}}
                        @isset($header)
                            <div class="flex items-center">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                </div>

                <div class="animate-in fade-in slide-in-from-bottom-2 duration-500">
                    {{ $slot }}
                </div>
            </main>

            {{-- Rodapé --}}
            <footer class="bg-white border-t border-gray-200/50 p-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://unpkg.com/imask"></script>
</body>

</html>