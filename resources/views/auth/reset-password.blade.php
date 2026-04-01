<!-- resources/views/auth/reset-password.blade.php -->
<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Nova Credencial</h2>
        <p class="text-xs text-slate-500 italic">Defina sua nova senha de acesso ao sistema</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('CONFIRMAR EMAIL')"
                class="text-[10px] font-bold tracking-widest text-slate-400 uppercase" />
            <x-text-input id="email" class="block mt-1 w-full border-slate-200 bg-slate-50 text-slate-500" type="email"
                name="email" :value="old('email', $request->email)" required autofocus autocomplete="username"
                readonly />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div class="mt-6">
            <x-input-label for="password" :value="__('NOVA SENHA')"
                class="text-[10px] font-bold tracking-widest text-slate-400 uppercase" />
            <x-text-input id="password"
                class="block mt-1 w-full border-slate-200 focus:border-slate-900 focus:ring-slate-900 shadow-sm"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="mt-6">
            <x-input-label for="password_confirmation" :value="__('REPETIR NOVA SENHA')"
                class="text-[10px] font-bold tracking-widest text-slate-400 uppercase" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full border-slate-200 focus:border-slate-900 focus:ring-slate-900 shadow-sm"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="mt-8">
            <x-primary-button
                class="w-full justify-center py-3 bg-slate-900 hover:bg-slate-800 text-xs tracking-widest font-black rounded-xl transition-all shadow-lg">
                {{ __('REDEFINIR ACESSO') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>