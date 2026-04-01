<!-- resources/views/auth/confirm-password.blade.php -->
<x-guest-layout>
    <div
        class="mb-6 p-4 bg-slate-50 border-l-4 border-slate-400 text-[11px] text-slate-600 uppercase tracking-tight font-medium italic">
        {{ __('Área de segurança crítica. Por favor, confirme sua senha para prosseguir com a operação.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('SENHA ATUAL')"
                class="text-[10px] font-bold tracking-widest text-slate-400 uppercase" />
            <x-text-input id="password"
                class="block mt-1 w-full border-slate-200 focus:border-slate-900 focus:ring-slate-900 shadow-sm"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="mt-8">
            <x-primary-button
                class="w-full justify-center py-3 bg-slate-900 hover:bg-slate-800 text-xs tracking-widest font-black rounded-xl transition-all shadow-lg">
                {{ __('CONFIRMAR IDENTIDADE') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>