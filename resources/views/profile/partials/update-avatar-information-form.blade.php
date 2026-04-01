<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 tracking-tight">
            {{ __('Avatar da Conta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __("Atualize o avatar da sua conta para identificação no sistema.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data"
        class="mt-6 space-y-6" x-data="{ photoName: null, photoPreview: null }">
        @csrf
        @method('patch')

        <div>
            {{-- Input de Arquivo Escondido --}}
            <input type="file" id="avatar" name="avatar" class="hidden" x-ref="avatar" x-on:change="
                        photoName = $event.target.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            photoPreview = e.target.result;
                        };
                        reader.readAsDataURL($event.target.files[0]);
                   ">

            <div class="flex items-center gap-6">
                {{-- Preview da Imagem Atual --}}
                <div class="relative" x-show="!photoPreview">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            class="h-20 w-20 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                    @else
                        <div
                            class="h-20 w-20 rounded-full bg-gray-900 flex items-center justify-center text-white font-black text-xl">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Preview da Nova Imagem --}}
                <div class="relative" x-show="photoPreview" x-cloak>
                    <span
                        class="block h-20 w-20 rounded-full border-2 border-blue-500 shadow-md object-cover bg-center bg-no-repeat bg-cover"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                {{-- Ações --}}
                <div class="flex flex-col gap-2">
                    <x-secondary-button type="button" x-on:click.prevent="$refs.avatar.click()">
                        {{ __('Selecionar Nova Foto') }}
                    </x-secondary-button>

                    @if(auth()->user()->avatar)
                        <button type="submit" form="delete-avatar-form"
                            class="text-left text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors uppercase tracking-widest px-2">
                            {{ __('Remover Foto') }}
                        </button>
                    @endif
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
            <x-primary-button>{{ __('Salvar Avatar') }}</x-primary-button>

            @if (session('status') === 'avatar-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600">{{ __('Atualizado com sucesso.') }}</p>
            @endif
        </div>
    </form>

    {{-- Formulário Oculto para Deletar Avatar --}}
    <form id="delete-avatar-form" method="post" action="{{ route('profile.avatar.destroy') }}" class="hidden">
        @csrf
        @method('delete')
    </form>
</section>