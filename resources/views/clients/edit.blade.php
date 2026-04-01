<!-- resources/views/clients/edit.blade.php -->
<x-app-layout title="Editar Cliente" subtitle="Atualize as informações do cliente ou lead cadastrado" x-data="{
         isDirty: false,
         originalData: {
             name: '{{ $client->name }}',
             email: '{{ $client->email }}',
             tax_id: '{{ $client->tax_id }}',
             phone: '{{ $client->phone }}',
             website: '{{ $client->website }}',
             status: '{{ $client->status }}',
             notes: `{{ $client->notes }}`
         },
         checkDirty() {
             this.isDirty = Object.keys(this.originalData).some(key => this.originalData[key] !== this.$refs.form[key].value);
         }
     }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cliente:') }} <span class="text-blue-600 font-bold">{{ $client->name }}</span>
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-full mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <div class="p-8">
                    <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Nome completo ou razão social')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="name" name="name" type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm"
                                    :value="old('name', $client->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('E-mail de contato')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="email" name="email" type="email"
                                    class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm"
                                    :value="old('email', $client->email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="tax_id" :value="__('Identificação fiscal')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="tax_id" name="tax_id" type="text"
                                    class="mask-tax-id mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm font-mono"
                                    :value="old('tax_id', $client->tax_id)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tax_id')" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Telefone')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="phone" name="phone" type="text"
                                    class="mask-phone mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm"
                                    :value="old('phone', $client->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                            </div>

                            <div>
                                <x-input-label for="website" :value="__('Website')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="website" name="website" type="url"
                                    class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm"
                                    :value="old('website', $client->website)" placeholder="https://..." />
                                <x-input-error class="mt-2" :messages="$errors->get('website')" />
                            </div>

                            <div x-data="{
                                open: false,
                                statusMap: {'lead': 'Lead', 'active': 'Ativo', 'inactive': 'Inativo'},
                                selectedValue: '{{ old('status', $client->status) }}',
                                get selectedName() { return this.statusMap[this.selectedValue] }
                            }">
                                <x-input-label :value="__('Status atual')" class="text-sm font-medium text-gray-700" />
                                <input type="hidden" name="status" x-model="selectedValue">
                                <div class="relative mt-1">
                                    <button type="button" @click="open = !open"
                                        class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2 text-left focus:ring-1 focus:ring-gray-900 text-sm">
                                        <span class="block truncate" x-text="selectedName"></span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </span>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-sm ring-1 ring-black ring-opacity-5">
                                        <template x-for="(label, value) in statusMap">
                                            <div @click="selectedValue = value; open = false"
                                                class="py-2 pl-3 hover:bg-gray-50 cursor-pointer transition-colors text-gray-900"
                                                x-text="label"></div>
                                        </template>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Observações internas')"
                                class="text-sm font-medium text-gray-700" />
                            <textarea id="notes" name="notes" rows="4"
                                class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm">{{ old('notes', $client->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('clients.index') }}"
                                class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">Cancelar</a>
                            <x-primary-button class="px-10 py-3">{{ __('Atualizar registro') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>