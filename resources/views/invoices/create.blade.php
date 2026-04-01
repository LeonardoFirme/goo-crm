<!-- resources/views/invoices/create.blade.php -->
<x-app-layout title="Gerar Fatura" subtitle="Emita cobranças e acompanhe o fluxo financeiro">

    <div class="py-10" x-data="{
        invoice_number: 'INV-{{ date('Ymd') }}-{{ strtoupper(Str::random(4)) }}',
        client_name: 'Selecione um cliente',
        client_id: '{{ old('client_id') }}',
        project_title: 'Selecione um projeto',
        project_id: '{{ old('project_id') }}',
        amount: '{{ old('amount') }}',
        due_date: '{{ old('due_date', date('Y-m-d')) }}',
        notes: '{{ old('notes') }}',
        status: '{{ old('status', 'pending') }}',

        allProjects: {{ $projects->map(fn($p) => ['id' => $p->id, 'title' => $p->title, 'client_id' => $p->client_id, 'budget' => number_format($p->budget, 2, ',', '.')])->toJson() }},

        get filteredProjects() {
            return this.allProjects.filter(p => p.client_id == this.client_id);
        },

        selectClient(id, name) {
            this.client_id = id;
            this.client_name = name;
            this.project_id = '';
            this.project_title = 'Selecione um projeto';
            this.amount = '';
        },

        selectProject(project) {
            this.project_id = project.id;
            this.project_title = project.title;
            this.amount = project.budget;
        }
    }">
        <div class="max-w-full mx-auto lg:px-8">

            {{-- Alertas de Sucesso ou Erro --}}
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-700 shadow-sm font-bold text-sm">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 shadow-sm">
                    <div class="flex items-center gap-3 mb-2 font-bold text-sm">
                        <i class="fas fa-exclamation-triangle"></i> Erro ao processar fatura:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Coluna do Formulário --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="p-8">
                            <form method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Número da Fatura --}}
                                    <div>
                                        <x-input-label for="invoice_number" :value="__('Número da fatura')"
                                            class="text-sm font-semibold" />
                                        <x-text-input id="invoice_number" name="invoice_number" type="text"
                                            x-model="invoice_number"
                                            class="mt-1 block w-full font-mono text-sm border-gray-300 rounded-lg"
                                            required />
                                    </div>

                                    {{-- Valor --}}
                                    <div>
                                        <x-input-label for="amount" :value="__('Valor da cobrança')"
                                            class="text-sm font-semibold" />
                                        <x-text-input id="amount" name="amount" type="text" x-model="amount"
                                            class="mask-money mt-1 block w-full border-gray-300 rounded-lg font-bold text-blue-600"
                                            placeholder="R$ 0,00" required />
                                    </div>

                                    {{-- Seletor de Cliente --}}
                                    <div x-data="{ open: false }">
                                        <x-input-label :value="__('Cliente')" class="text-sm font-semibold" />
                                        <input type="hidden" name="client_id" x-model="client_id">
                                        <div class="relative mt-1">
                                            <button type="button" @click="open = !open"
                                                class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left text-sm">
                                                <span class="block truncate" x-text="client_name"></span>
                                                <span
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400 font-black"><i
                                                        class="fas fa-chevron-down text-[10px]"></i></span>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak
                                                class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-md py-1 border text-sm overflow-auto">
                                                @foreach($clients as $client)
                                                    <div @click="selectClient('{{ $client->id }}', '{{ $client->name }}'); open = false"
                                                        class="py-2.5 pl-3 hover:bg-slate-50 cursor-pointer border-b last:border-0 border-slate-50 text-slate-700">
                                                        {{ $client->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Seletor de Projeto --}}
                                    <div x-data="{ open: false }">
                                        <x-input-label :value="__('Projeto / Serviço')" class="text-sm font-semibold" />
                                        <input type="hidden" name="project_id" x-model="project_id">
                                        <div class="relative mt-1">
                                            <button type="button" @click="open = !open" :disabled="!client_id"
                                                :class="!client_id ? 'bg-slate-50 cursor-not-allowed opacity-60' : ''"
                                                class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left text-sm transition-all">
                                                <span class="block truncate" x-text="project_title"></span>
                                                <span
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400 font-black"><i
                                                        class="fas fa-chevron-down text-[10px]"></i></span>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak
                                                class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-md py-1 border text-sm overflow-auto">
                                                <template x-if="filteredProjects.length === 0">
                                                    <div class="py-2.5 pl-3 text-slate-400 italic">Nenhum projeto
                                                        disponível</div>
                                                </template>
                                                <template x-for="project in filteredProjects" :key="project.id">
                                                    <div @click="selectProject(project); open = false"
                                                        class="py-2.5 pl-3 hover:bg-slate-50 cursor-pointer border-b last:border-0 border-slate-50 text-slate-700">
                                                        <span x-text="project.title"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Vencimento --}}
                                    <div>
                                        <x-input-label for="due_date" :value="__('Data de vencimento')"
                                            class="text-sm font-semibold" />
                                        <x-text-input id="due_date" name="due_date" type="date" x-model="due_date"
                                            class="mt-1 block w-full border-gray-300 rounded-lg text-sm" required />
                                    </div>

                                    {{-- Status --}}
                                    <div x-data="{ open: false }">
                                        <x-input-label :value="__('Status do pagamento')"
                                            class="text-sm font-semibold" />
                                        <input type="hidden" name="status" x-model="status">
                                        <div class="relative mt-1">
                                            <button type="button" @click="open = !open"
                                                class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left text-sm">
                                                <span
                                                    x-text="status === 'pending' ? 'Pendente' : (status === 'paid' ? 'Pago' : 'Atrasado')"></span>
                                                <span
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400 font-black"><i
                                                        class="fas fa-chevron-down text-[10px]"></i></span>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-cloak
                                                class="absolute z-20 mt-1 w-full bg-white shadow-xl rounded-md py-1 border text-sm">
                                                <div @click="status = 'pending'; open = false"
                                                    class="py-2.5 pl-3 hover:bg-slate-50 cursor-pointer">Pendente</div>
                                                <div @click="status = 'paid'; open = false"
                                                    class="py-2.5 pl-3 hover:bg-slate-50 cursor-pointer">Pago</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Notas/Descrição --}}
                                <div>
                                    <x-input-label for="notes" :value="__('Descrição detalhada')"
                                        class="text-sm font-semibold" />
                                    <textarea id="notes" name="notes" rows="3" x-model="notes"
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm text-sm"
                                        placeholder="Ex: Pagamento referente à primeira parcela..."></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-100">
                                    <a href="{{ route('invoices.index') }}"
                                        class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors">Cancelar</a>
                                    <x-primary-button
                                        class="px-8 py-3 bg-slate-900 shadow-xl shadow-slate-900/10">Confirmar e
                                        emitir</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Review Profissional --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-10">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 ml-1">
                            Visualização do documento</p>

                        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                                        <i class="fas fa-file-invoice text-lg text-white"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Referência
                                    </p>
                                    <p class="font-mono text-sm" x-text="invoice_number"></p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-tighter transition-colors"
                                        :class="status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400'">
                                        <span x-text="status === 'paid' ? 'Liquidada' : 'Aguardando'"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Pagador</p>
                                    <p class="text-sm font-bold" x-text="client_name"></p>
                                </div>

                                <div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Serviço / Projeto</p>
                                    <p class="text-sm font-medium text-slate-300" x-text="project_title"></p>
                                </div>

                                <div class="pt-6 border-t border-slate-800">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                                Vencimento</p>
                                            <p class="text-sm font-bold text-slate-400"
                                                x-text="due_date.split('-').reverse().join('/')"></p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                                Valor Total</p>
                                            <p class="text-2xl font-black tracking-tighter text-blue-400"
                                                x-text="amount || 'R$ 0,00'"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Descrição no Review --}}
                                <div class="pt-6 border-t border-slate-800" x-show="notes.length > 0" x-transition>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Descrição</p>
                                    <p class="text-[11px] leading-relaxed text-slate-400 italic" x-text="notes"></p>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-center">
                                <div
                                    class="w-full py-3 border border-dashed border-slate-700 rounded-lg flex flex-col items-center justify-center opacity-40">
                                    <i class="fas fa-qrcode mb-2"></i>
                                    <span
                                        class="text-[9px] font-bold uppercase tracking-widest">Autenticidade
                                        Garantida</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>