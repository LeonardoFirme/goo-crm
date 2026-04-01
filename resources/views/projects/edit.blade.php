<!-- resources/views/projects/edit.blade.php -->
<x-app-layout title="Editar Projeto" subtitle="Atualize as informações do projeto e acompanhe o progresso das entregas"
    x-data="{
         isDirty: false,
         originalData: {
             title: '{{ $project->title }}',
             client_id: '{{ $project->client_id }}',
             start_date: '{{ $project->start_date->format('Y-m-d') }}',
             deadline: '{{ $project->deadline ? $project->deadline->format('Y-m-d') : '' }}',
             budget: '{{ number_format($project->budget, 2, ',', '.') }}',
             status: '{{ $project->status }}',
             description: `{{ $project->description }}`
         },
         checkDirty() {
             this.isDirty = Object.keys(this.originalData).some(key => this.originalData[key] !== this.$refs.form[key].value);
         }
     }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projeto:') }} <span class="text-blue-600 font-bold">{{ $project->title }}</span>
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="mx-auto max-w-full">

            {{-- Listagem de Erros para Debug (Apenas para garantir que você veja o que está falhando) --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-700 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-exclamation-triangle text-rose-500"></i>
                        <span class="text-sm font-bold">{{ __('Ops! Verifique os campos abaixo:') }}</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <div class="p-8">
                    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Dropdown de Cliente com SearchView --}}
                            <div class="md:col-span-2" x-data="{
                                    open: false,
                                    search: '',
                                    selectedName: '{{ old('client_id') ? ($clients->firstWhere('id', old('client_id'))->name ?? '') : $project->client->name }}',
                                    selectedValue: '{{ old('client_id', $project->client_id) }}',
                                    clients: {{ $clients->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
                                    get filteredClients() {
                                        if (this.search === '') return this.clients;
                                        return this.clients.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                                    }
                                }">
                                <x-input-label :value="__('Cliente vinculado')"
                                    class="text-sm font-medium text-gray-700" />
                                <input type="hidden" name="client_id" x-model="selectedValue">

                                <div class="relative mt-1">
                                    <button type="button"
                                        @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                        class="relative w-full bg-white border {{ $errors->has('client_id') ? 'border-rose-500 ring-1 ring-rose-500' : 'border-gray-300' }} rounded-lg pl-3 pr-10 py-2.5 text-left text-sm focus:ring-1 focus:ring-gray-900 transition-all">
                                        <span class="block truncate" x-text="selectedName"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </span>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute z-50 mt-1 w-full bg-white shadow-xl rounded-xl border border-gray-200 py-1 text-sm overflow-hidden flex flex-col max-h-64">
                                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                                            <input type="text" x-ref="searchInput" x-model="search"
                                                placeholder="Pesquisar cliente..."
                                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-xs focus:ring-1 focus:ring-gray-900">
                                        </div>
                                        <div class="overflow-auto flex-1">
                                            <template x-for="client in filteredClients" :key="client.id">
                                                <div @click="selectedValue = client.id; selectedName = client.name; open = false; search = ''"
                                                    class="py-2.5 px-4 hover:bg-gray-50 cursor-pointer text-gray-700 border-b border-gray-50 last:border-0"
                                                    x-text="client.name"></div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="title" :value="__('Título do projeto')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="title" name="title" type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm {{ $errors->has('title') ? 'border-rose-500 ring-rose-500' : '' }}"
                                    :value="old('title', $project->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="start_date" :value="__('Data de início')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="start_date" name="start_date" type="date"
                                    class="mt-1 block w-full border-gray-300 focus:ring-gray-900 rounded-lg shadow-sm text-sm {{ $errors->has('start_date') ? 'border-rose-500 ring-rose-500' : '' }}"
                                    :value="old('start_date', $project->start_date->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>

                            <div>
                                <x-input-label for="deadline" :value="__('Prazo final')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="deadline" name="deadline" type="date"
                                    class="mt-1 block w-full border-gray-300 focus:ring-gray-900 rounded-lg shadow-sm text-sm {{ $errors->has('deadline') ? 'border-rose-500 ring-rose-500' : '' }}"
                                    :value="old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                            </div>

                            <div>
                                <x-input-label for="budget" :value="__('Orçamento atualizado')"
                                    class="text-sm font-medium text-gray-700" />
                                <x-text-input id="budget" name="budget" type="text"
                                    class="mask-money mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm font-semibold {{ $errors->has('budget') ? 'border-rose-500 ring-rose-500' : '' }}"
                                    :value="old('budget', number_format($project->budget, 2, ',', '.'))" required />
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
                                selectedValue: '{{ old('status', $project->status) }}',
                                get selectedName() { return this.statusMap[this.selectedValue] }
                            }">
                                <x-input-label :value="__('Status do workflow')"
                                    class="text-sm font-medium text-gray-700" />
                                <input type="hidden" name="status" x-model="selectedValue">
                                <div class="relative mt-1">
                                    <button type="button" @click="open = !open"
                                        class="relative w-full bg-white border {{ $errors->has('status') ? 'border-rose-500 ring-rose-500' : 'border-gray-300' }} rounded-lg pl-3 pr-10 py-2.5 text-left text-sm focus:ring-1 focus:ring-gray-900">
                                        <span x-text="selectedName"></span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </span>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute z-10 mt-2 w-full bg-white shadow-xl rounded-xl py-2 text-sm border border-gray-200">
                                        <template x-for="(label, value) in statusMap">
                                            <div @click="selectedValue = value; open = false"
                                                class="py-2.5 px-4 hover:bg-gray-50 cursor-pointer transition-colors text-gray-700 font-medium"
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
                                class="mt-1 block w-full border-gray-300 focus:border-gray-900 rounded-lg shadow-sm text-sm {{ $errors->has('description') ? 'border-rose-500' : '' }}"
                                placeholder="Notas técnicas e descrição...">{{ old('description', $project->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center justify-end gap-6 pt-8 border-t border-gray-100">
                            <a href="{{ route('projects.index') }}"
                                class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition-colors">Cancelar</a>
                            <x-primary-button class="px-12 py-3.5 shadow-lg shadow-gray-900/10">
                                {{ __('Atualizar projeto') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>