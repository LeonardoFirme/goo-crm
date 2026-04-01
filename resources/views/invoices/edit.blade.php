<!-- resources/views/invoices/edit.blade.php -->
<x-app-layout title="Editar Fatura" subtitle="Atualize os detalhes e status da cobrança">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight px-6">
            {{ __('Fatura:') }} <span class="text-blue-600 font-mono">#{{ $invoice->invoice_number }}</span>
        </h2>
    </x-slot>

    <div class="py-10" x-data="{
        invoice_number: '{{ $invoice->invoice_number }}',
        client_name: '{{ $invoice->client->name }}',
        client_id: '{{ old('client_id', $invoice->client_id) }}',
        project_title: '{{ $invoice->project->title }}',
        project_id: '{{ old('project_id', $invoice->project_id) }}',
        amount: '{{ old('amount', number_format($invoice->amount, 2, ',', '.')) }}',
        due_date: '{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}',
        notes: '{{ old('notes', $invoice->notes) }}',
        status: '{{ old('status', $invoice->status) }}',
        payment_method: '{{ old('payment_method', $invoice->payment_method ?? '') }}',
        isAlreadyPaid: {{ $invoice->status === 'paid' ? 'true' : 'false' }},
        isLocked() { return this.isAlreadyPaid }
    }">
        <div class="max-w-full mx-auto">

            {{-- Alerta de Fatura Paga --}}
            <template x-if="isLocked()">
                <div
                    class="mb-6 p-4 bg-gray-900 border border-gray-800 rounded-xl flex items-center justify-between text-white shadow-lg animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-gray-900 shadow-lg shadow-emerald-500/20">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black tracking-tight uppercase text-[10px]">Fatura
                                Liquidada</p>
                            <p class="text-[11px] text-gray-400 font-medium">Este documento já foi processado e os
                                dados originais foram preservados para auditoria.</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('invoices.download', $invoice) }}" target="_blank"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-bold text-xs transition-all shadow-lg shadow-blue-600/20">
                            <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                        </a>
                    </div>
                </div>
            </template>

            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-700 shadow-sm font-bold text-sm">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Formulário / Detalhes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200/50">
                        <div class="p-8">
                            <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="client_id" x-model="client_id">
                                <input type="hidden" name="project_id" x-model="project_id">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label :value="__('Referência do Documento')"
                                            class="text-xs font-bold text-gray-500 uppercase" />
                                        <x-text-input
                                            class="mt-1.5 block w-full font-mono bg-gray-50 border-gray-200 text-gray-500 cursor-not-allowed"
                                            :value="$invoice->invoice_number" readonly />
                                    </div>

                                    <div>
                                        <x-input-label for="amount" :value="__('Valor Liquidado')"
                                            class="text-xs font-bold text-gray-500 uppercase" />
                                        <x-text-input id="amount" name="amount" type="text" x-model="amount"
                                            class="mask-money mt-1.5 block w-full border-gray-300 focus:ring-gray-900 rounded-lg font-bold"
                                            ::class="isLocked() ? 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200 shadow-none' : ''"
                                            ::readonly="isLocked()" required />
                                    </div>

                                    <div>
                                        <x-input-label for="due_date" :value="__('Vencimento')"
                                            class="text-xs font-bold text-gray-500 uppercase" />
                                        <x-text-input id="due_date" name="due_date" type="date" x-model="due_date"
                                            class="mt-1.5 block w-full border-gray-300 rounded-lg text-sm"
                                            ::class="isLocked() ? 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200' : ''"
                                            ::readonly="isLocked()" required />
                                    </div>

                                    <div x-data="{ open: false }">
                                        <x-input-label :value="__('Status Financeiro')"
                                            class="text-xs font-bold text-gray-500 uppercase" />
                                        <input type="hidden" name="status" x-model="status">
                                        <div class="relative mt-1.5">
                                            <button type="button" @click="if(!isLocked()) open = !open"
                                                class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left text-sm font-bold"
                                                ::class="isLocked() ? 'bg-gray-50 border-gray-200 cursor-not-allowed text-emerald-600' : 'text-gray-700'">
                                                <span
                                                    x-text="status === 'pending' ? 'Pendente' : (status === 'paid' ? 'Pago' : (status === 'overdue' ? 'Atrasado' : 'Cancelado'))"></span>
                                                <span
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400"><i
                                                        class="fas fa-chevron-down text-[10px]"></i></span>
                                            </button>
                                            <div x-show="open && !isLocked()" @click.away="open = false" x-cloak
                                                class="absolute z-50 mt-1 w-full bg-white shadow-xl rounded-md py-1 border text-sm">
                                                <div @click="status = 'pending'; open = false"
                                                    class="py-2.5 pl-3 hover:bg-gray-50 cursor-pointer">Pendente</div>
                                                <div @click="status = 'paid'; open = false"
                                                    class="py-2.5 pl-3 hover:bg-gray-50 cursor-pointer">Pago</div>
                                                <div @click="status = 'overdue'; open = false"
                                                    class="py-2.5 pl-3 hover:bg-gray-50 cursor-pointer text-rose-600 font-bold">
                                                    Atrasado</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Métodos de Pagamento --}}
                                    <div x-show="status === 'paid'" x-transition x-cloak class="md:col-span-2">
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                            <x-input-label :value="__('Método de Recebimento')"
                                                class="text-[10px] font-bold text-gray-500 mb-2 uppercase tracking-widest" />
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                @foreach(['Dinheiro', 'Pix', 'Crédito', 'Débito', 'Boleto'] as $method)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="payment_method" value="{{ $method }}"
                                                            x-model="payment_method" class="hidden peer"
                                                            ::disabled="isLocked()">
                                                        <div
                                                            class="py-2 text-center text-[11px] font-bold rounded-lg border border-gray-300 bg-white peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 peer-disabled:opacity-50 transition-all">
                                                            {{ $method }}
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="notes" :value="__('Notas e Observações')"
                                        class="text-xs font-bold text-gray-500 uppercase" />
                                    <textarea id="notes" name="notes" rows="3" x-model="notes"
                                        class="mt-1.5 block w-full border-gray-300 rounded-lg text-sm"
                                        ::class="isLocked() ? 'bg-gray-50 border-gray-200 text-gray-400 cursor-not-allowed shadow-none' : ''"
                                        ::readonly="isLocked()"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                                    <a href="{{ route('invoices.index') }}"
                                        class="text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors">Voltar
                                        para Listagem</a>

                                    <x-primary-button
                                        class="px-8 py-3 bg-gray-900 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        ::disabled="isLocked()">
                                        <span x-text="isLocked() ? 'Registro Bloqueado' : 'Salvar alterações'"></span>
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Review de Auditoria --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-10">
                        <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden transition-all duration-500"
                            :class="isLocked() ? 'ring-4 ring-emerald-500/10' : ''">

                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mb-4 transition-colors"
                                        :class="isLocked() ? 'bg-emerald-500' : 'bg-blue-600'">
                                        <i class="fas text-lg text-white"
                                            :class="isLocked() ? 'fa-check-double' : 'fa-file-invoice'"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Documento
                                        No.</p>
                                    <p class="font-mono text-sm" x-text="invoice_number"></p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider"
                                        :class="{'bg-emerald-500 text-white': status === 'paid', 'bg-amber-500 text-white': status === 'pending', 'bg-rose-500 text-white': status === 'overdue'}">
                                        <span
                                            x-text="status === 'paid' ? 'Liquidada' : (status === 'pending' ? 'Pendente' : 'Atrasada')"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-6 text-left leading-tight">
                                {{-- Emissor --}}
                                <div class="pb-4 border-b border-gray-800">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                                        Responsável</p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center text-[10px] font-black text-blue-400">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cliente --}}
                                <div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">
                                        Destinatário</p>
                                    <p class="text-sm font-bold text-white truncate" x-text="client_name"></p>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $invoice->client->tax_id }}</p>
                                </div>

                                {{-- Pagamento --}}
                                <div x-show="status === 'paid' && payment_method" x-transition>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">
                                        Confirmado via</p>
                                    <div class="flex items-center gap-2 text-emerald-400">
                                        <i class="fas fa-check-circle text-[10px]"></i>
                                        <p class="text-sm font-bold tracking-tight" x-text="payment_method"></p>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-800">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p
                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">
                                                Vencimento</p>
                                            <p class="text-sm font-bold text-gray-300"
                                                x-text="due_date.split('-').reverse().join('/')"></p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">
                                                Total Geral</p>
                                            <p class="text-2xl font-black transition-colors tracking-tighter"
                                                :class="isLocked() ? 'text-emerald-400' : 'text-blue-400'"
                                                x-text="amount || 'R$ 0,00'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botão de Download persistente na review --}}
                            <div class="mt-8">
                                <a href="{{ route('invoices.download', $invoice) }}" target="_blank"
                                    class="w-full py-3.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-xl flex items-center justify-center gap-3 transition-all group font-bold text-[11px] text-white tracking-widest uppercase">
                                    <i
                                        class="fas fa-cloud-download-alt text-gray-500 group-hover:text-blue-400 transition-colors"></i>
                                    Gerar Comprovante
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>