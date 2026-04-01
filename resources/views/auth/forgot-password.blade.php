<!-- resources/views/auth/forgot-password.blade.php -->
<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Recuperar Acesso</h2>
        <p class="mt-2 text-xs text-slate-500 italic">
            {{ __('Informe seu e-mail corporativo para receber as instruções de redefinição.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4 text-xs font-bold text-emerald-600" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('EMAIL DE CADASTRO')"
                class="text-[10px] font-bold tracking-widest text-slate-400 uppercase" />
            <x-text-input id="email"
                class="block mt-1 w-full border-slate-200 focus:border-slate-900 focus:ring-slate-900 shadow-sm"
                type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div class="mt-8 flex flex-col gap-4">
            <x-primary-button
                class="w-full justify-center py-3 bg-slate-900 hover:bg-slate-800 text-xs tracking-widest font-black rounded-xl transition-all shadow-lg">
                {{ __('ENVIAR LINK DE RECUPERAÇÃO') }}
            </x-primary-button>

            <a href="{{ route('login') }}"
                class="text-center text-[11px] font-bold text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-widest">
                {{ __('Voltar ao login') }}
            </a>
        </div>
    </form>
</x-guest-layout>