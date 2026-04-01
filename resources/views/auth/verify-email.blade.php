<!-- resources/views/auth/verify-email.blade.php -->
<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Validar Conta</h2>
        <p class="mt-2 text-xs text-slate-600 italic">
            {{ __('Para sua segurança, confirme seu endereço de e-mail através do link enviado.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div
            class="mb-6 p-4 bg-emerald-50 border-emerald-200 border text-[11px] font-bold text-emerald-700 uppercase tracking-tight">
            {{ __('Um novo link de verificação foi enviado ao seu endereço corporativo.') }}
        </div>
    @endif

    <div class="mt-8 flex flex-col gap-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button
                class="w-full justify-center py-3 bg-slate-900 hover:bg-slate-800 text-[10px] tracking-widest font-black rounded-xl shadow-lg">
                {{ __('REENVIAR EMAIL DE VALIDAÇÃO') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit"
                class="text-[11px] font-bold text-slate-400 hover:text-rose-600 transition-colors uppercase tracking-widest italic underline decoration-slate-200 underline-offset-4">
                {{ __('Encerrar Sessão') }}
            </button>
        </form>
    </div>
</x-guest-layout>