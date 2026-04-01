<!-- resources/views/projects/create.blade.php -->
<x-app-layout title="Novo Projeto" subtitle="Inicie um novo projeto vinculando-o a um cliente cadastrado">

    <div class="py-0">
        <div class="max-w-full mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <div class="p-8">
                    <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Dropdown de Cliente com SearchView --}}
                            <div class="md:col-span-2" x-data="{
                                    open: false,
                                    search: '',
                                    selectedName: '{{ old('client_id') ? ($clients->firstWhere('id', old('client_id'))->name ?? 'Selecione um cliente') : 'Selecione um cliente' }}',
                                    selectedValue: '{{ old('client_id') }}',
                                    clients: {{ $clients->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'tax_id' => $c->tax_id])->toJson() }},
                                    get filteredClients() {
                                        if (this.search === '') return this.clients;
                                        return this.clients.filter(c =>
                                            c.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                            c.tax_id.includes(this.search)
                                        );
                                    }
                                }">
                                <x-input-label :value="__('Cliente vinculado')"
                                    class="text-sm font-medium text-gray-700" />
                                <input type="hidden" name="client_id" x-model="selectedValue" required>

                                <div class="relative mt-1">
                                    <button type="button"
                                        @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                        class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left focus:outline-none focus:ring-2 focus:ring-gray-900/5 focus:border-gray-900 text-sm transition-all">
                                        <span class="block truncate" x-text="selectedName"></span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                                :class="open ? 'rotate-180' : ''"></i>
                                        </span>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute z-50 mt-2 w-full bg-white shadow-xl max-h-72 rounded-xl border border-gray-200 overflow-hidden flex flex-col">

                                        {{-- SearchView Input --}}
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <div class="relative">
                                                <i
                                                    class="fas fa-search absolute left-3 top-1/2 -trangray-y-1/2 text-gray-400 text-xs"></i>
                                                <input type="text" x-ref="searchInput" x-model="search"
                                                    placeholder="Buscar por nome ou documento..."
                                                    class="w-full pl-8 pr-4 py-2 bg-white border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-gray-900/5 focus:border-gray-400 placeholder-gray-400">
                                            </div>
                                        </div>

                                        {{-- Listagem --}}
                                        <div
                                            class="overflow-y-auto overflow-x-hidden flex-1 scrollbar-thin scrollbar-thumb-gray-200">
                                            <template x-for="client in filteredClients" :key="client.id">
                                                <div @click="selectedValue = client.id; selectedName = client.name; open = false; search = ''"
                                                    class="group text-gray-700 cursor-pointer select-none relative py-3 pl-4 pr-9 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="font-medium group-hover:text-gray-900 transition-colors text-sm"
                                                            x-text="client.name"></span>
                                                        <span class="text-gray-400 text-[11px] font-mono"
                                                            x-text="client.tax_id"></span>
                                                    </div>
                                                    <span x-show="selectedValue == client.id"
                                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-900">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </span>
                                                </div>
                                            </template>

                                            <div x-show="filteredClients.length === 0" class="py-8 px-4 text-center">
                                                <i class="fas fa-user-slash text-gray-200 text-2xl mb-2"></i>
                                                <p class="text-gray-400 text-xs font-medium">Nenhum cliente encontrado.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="title" :value="__('Título do projeto')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="title" name="title" type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-lg shadow-sm text-sm" placeholder="Dê um nome para o seu projeto..."
                                    :value="old('title')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="start_date" :value="__('Data de início')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="start_date" name="start_date" type="date"
                                    class="mt-1 block w-full border-gray-300 focus:ring-gray-900 rounded-lg text-sm"
                                    :value="old('start_date', date('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>

                            <div>
                                <x-input-label for="deadline" :value="__('Prazo final (deadline)')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="deadline" name="deadline" type="date"
                                    class="mt-1 block w-full border-gray-300 focus:ring-gray-900 rounded-lg text-sm"
                                    :value="old('deadline')" />
                                <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                            </div>

                            <div>
                                <x-input-label for="budget" :value="__('Orçamento previsto')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="budget" name="budget" type="text"
                                    class="mask-money mt-1 block w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-lg shadow-sm text-sm font-semibold" placeholder="R$ 0,00"
                                    :value="old('budget')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('budget')" />
                            </div>

                            <div x-data="{
                                open: false,
                                statusMap: {
                                    'planning': 'Planejamento',
                                    'in_progress': 'Em andamento',
                                    'on_hold': 'Em espera',
                                    'completed': 'Concluído',
                                    'cancelled': 'Cancelado'
                                },
                                selectedValue: '{{ old('status', 'planning') }}',
                                get selectedName() { return this.statusMap[this.selectedValue] }
                            }">
                                <x-input-label :value="__('Status do workflow')"
                                    class="text-sm font-medium text-gray-700" />
                                <input type="hidden" name="status" x-model="selectedValue">
                                <div class="relative mt-1">
                                    <button type="button" @click="open = !open"
                                        class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-2.5 text-left focus:ring-2 focus:ring-gray-900/5 focus:border-gray-900 text-sm transition-all">
                                        <span x-text="selectedName"></span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </span>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak x-transition
                                        class="absolute z-50 mt-2 w-full bg-white shadow-xl rounded-xl py-2 text-sm border border-gray-200">
                                        <template x-for="(label, value) in statusMap">
                                            <div @click="selectedValue = value; open = false"
                                                class="py-2.5 px-4 hover:bg-gray-50 cursor-pointer transition-colors text-gray-700 font-medium hover:text-gray-900"
                                                x-text="label"></div>
                                        </template>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Escopo e descrição')"
                                class="text-sm font-medium text-gray-700" />
                            <textarea id="description" name="description" rows="5"
                                class="mt-1 block w-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 rounded-lg text-sm shadow-sm"
                                placeholder="Descreva os objetivos e entregáveis do projeto...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-8 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}"
                                class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">Cancelar</a>
                            <x-primary-button class="px-12 py-3.5 shadow-lg shadow-gray-900/10">
                                {{ __('Iniciar projeto') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>