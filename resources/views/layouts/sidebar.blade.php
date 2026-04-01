<!-- resources/views/layouts/sidebar.blade.php -->
<aside
    class="fixed inset-y-0 left-0 z-50 w-[300px] bg-gradient-to-b from-slate-950 via-slate-800 to-slate-900 text-slate-300 transition-all duration-300 transform lg:static lg:inset-0 lg:translate-x-0"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    {{-- Header da Sidebar --}}
    <div class="flex items-center justify-start h-16 bg-slate-950/30 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="block w-11 h-11 fill-current text-white" />
            <span class="text-xl font-black tracking-tighter text-white">ERP<span
                    class="text-blue-600">Simples</span></span>
        </a>
    </div>

    {{-- Navegação --}}
    <nav class="mt-6 px-3 space-y-1 overflow-y-auto" style="height: calc(100vh - 160px);">
        <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">Menu Principal</p>

        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fas fa-chart-line">
            {{ __('Dashboard') }}
        </x-sidebar-link>

        <x-sidebar-link :href="route('clients.index')" :active="request()->routeIs('clients.*')" icon="fas fa-users">
            {{ __('Clientes') }}
        </x-sidebar-link>

        <x-sidebar-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
            icon="fas fa-project-diagram">
            {{ __('Projetos') }}
        </x-sidebar-link>

        <x-sidebar-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')"
            icon="fas fa-file-invoice-dollar">
            {{ __('Faturas') }}
        </x-sidebar-link>

        <div class="pt-10">
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-4">Configurações</p>
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                icon="fas fa-user-cog">
                {{ __('Meu Perfil') }}
            </x-sidebar-link>
        </div>
    </nav>

    {{-- Rodapé da Sidebar (User Info) --}}
    <div class="absolute bottom-0 w-full p-4 bg-slate-900/50">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-2 group">
            <div
                class="h-9 w-9 rounded-lg overflow-hidden ring-2 ring-white/5 group-hover:ring-blue-500/50 transition-all shadow-inner flex-shrink-0">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                        class="h-full w-full object-cover">
                @else
                    <div
                        class="h-full w-full bg-blue-900 flex items-center justify-center text-[10px] font-black text-white uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                @endif
            </div>

            <div class="flex-1 overflow-hidden">
                <p
                    class="text-[11px] font-bold text-white truncate leading-none mb-1 group-hover:text-blue-400 transition-colors">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[9px] text-slate-500 truncate font-medium tracking-tighter">
                    {{ Str::limit(Auth::user()->email, 32) }}
                </p>
            </div>
        </a>
    </div>
</aside>

{{-- Overlay para fechar a sidebar no mobile ao clicar fora --}}
<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden">
</div>