<!-- resources/views/auth/register.blade.php -->
<x-guest-layout title="Novo Operador">
    {{-- Cabeçalho Minimalista --}}
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black text-gray-900 tracking-tighter">
            {{ __('Criar nova conta') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Crie sua conta para acesso ao sistema</p>
    </div>

    {{-- Inicialização do Estado Global do Formulário com Alpine.js --}}
    <form method="POST" action="{{ route('register') }}" x-data="{
        showPass: false,
        showConfirm: false,
        acceptedTerms: false
    }">
        @csrf

        {{-- Nome Completo --}}
        <div>
            <x-input-label for="name" :value="__('Nome completo')"
                class="text-xs font-semibold tracking-tight text-gray-700" />
            <x-text-input id="name"
                class="block mt-1.5 w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- E-mail --}}
        <div class="mt-6">
            <x-input-label for="email" :value="__('E-mail corporativo')"
                class="text-xs font-semibold tracking-tight text-gray-700" />
            <x-text-input id="email"
                class="block mt-1.5 w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- Senha com Toggle --}}
        <div class="mt-6">
            <x-input-label for="password" :value="__('Definir senha')"
                class="text-xs font-semibold tracking-tight text-gray-700" />
            <div class="relative mt-1.5">
                <x-text-input id="password"
                    class="block w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm pr-10"
                    ::type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password" />

                <button type="button" @click="showPass = !showPass"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-900 transition-colors">
                    <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- Confirmação de Senha com Toggle --}}
        <div class="mt-6">
            <x-input-label for="password_confirmation" :value="__('Confirmar senha')"
                class="text-xs font-semibold tracking-tight text-gray-700" />
            <div class="relative mt-1.5">
                <x-text-input id="password_confirmation"
                    class="block w-full border-gray-200 focus:border-gray-900 focus:ring-gray-900 shadow-sm rounded-lg text-sm pr-10"
                    ::type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                    autocomplete="new-password" />

                <button type="button" @click="showConfirm = !showConfirm"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-900 transition-colors">
                    <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-medium" />
        </div>

        {{-- Checkbox de Consentimento --}}
        <div class="mt-8">
            <label for="consent" class="inline-flex items-center cursor-pointer group">
                <input id="consent" type="checkbox"
                    class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 focus:ring-offset-0 transition-all cursor-pointer"
                    x-model="acceptedTerms">
                <span
                    class="ml-2 text-[11px] font-medium text-gray-500 group-hover:text-gray-700 transition-colors selection:bg-transparent">
                    {{ __('Estou ciente do tratamento de dados conforme as diretrizes do Brasil de Lei N. 13.709/2018 sobre Proteção de Dados Pessoais (LGPD).') }}
                </span>
            </label>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col gap-4 mt-8">
            {{-- O botão é habilitado apenas quando acceptedTerms é verdadeiro --}}
            <x-primary-button
                class="w-full justify-center py-3.5 text-sm font-bold rounded-xl transition-all shadow-md active:scale-[0.99] disabled:opacity-30 disabled:grayscale disabled:cursor-not-allowed"
                ::disabled="!acceptedTerms">
                {{ __('Finalizar cadastro') }}
            </x-primary-button>

            <a class="text-center text-xs font-semibold text-gray-400 hover:text-gray-900 transition-colors py-2"
                href="{{ route('login') }}">
                {{ __('Já possui uma conta? Acessar') }}
            </a>
        </div>
    </form>
</x-guest-layout>