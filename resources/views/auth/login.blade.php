<!-- resources/views/auth/login.blade.php -->
<x-guest-layout title="Acesso ao Sistema">
    {{-- Status da Sessão --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Cabeçalho Minimalista --}}
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Acesso Restrito</h2>
        <p class="text-sm text-gray-500 mt-1">Identifique-se para gerenciar o sistema</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- E-mail --}}
        <div>
            <x-input-label for="email" :value="__('E-mail corporativo')"
                class="text-xs font-semibold tracking-tight text-gray-700" />
            <x-text-input id="email"
                class="block mt-1.5 w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- Senha --}}
        <div class="mt-6">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Senha de acesso')"
                    class="text-xs font-semibold tracking-tight text-gray-700" />
            </div>
            <x-text-input id="password"
                class="block mt-1.5 w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- Lembrar Dispositivo --}}
        <div class="block mt-6">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded-md border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 focus:ring-offset-0 transition-all"
                    name="remember">
                <span class="ms-2 text-xs font-medium text-gray-500 group-hover:text-gray-700 transition-colors">
                    {{ __('Lembrar neste dispositivo') }}
                </span>
            </label>
        </div>

        {{-- Ações de Autenticação --}}
        <div class="flex flex-col gap-4 mt-10">
            <x-primary-button
                class="w-full justify-center py-3.5 text-sm font-bold rounded-xl transition-all shadow-md active:scale-[0.99]">
                {{ __('Entrar no sistema') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a class="text-center text-xs font-semibold text-gray-400 hover:text-gray-900 transition-colors py-2"
                    href="{{ route('password.request') }}">
                    {{ __('Recuperar credenciais de acesso') }}
                </a>
            @endif

            <a class="text-center text-xs font-semibold text-gray-400 hover:text-gray-900 transition-colors py-2"
                href="{{ route('register') }}">
                {{ __('Ainda não tem uma conta? Criar Conta') }}
            </a>
        </div>
    </form>
</x-guest-layout>