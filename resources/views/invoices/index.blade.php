<!-- resources/views/invoices/index.blade.php -->
<x-app-layout title="Faturas" subtitle="Gerencie as cobranças e fluxo financeiro">
    <x-slot name="header">
        <div class="flex justify-between items-center px-6">
            <a href="{{ route('invoices.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-lg font-bold text-xs text-white shadow-sm hover:bg-blue-950 transition ease-in-out duration-150">
                Gerar fatura
            </a>
        </div>
    </x-slot>

    <div class="py-0" x-data="{
        showRefundModal: false,
        invoiceToRefund: '',
        invoiceConfirmationInput: '',
        refundRoute: ''
    }">
        <div class="max-w-full mx-auto sm:px-6">

            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Barra de Busca --}}
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-base font-semibold text-blue-800">Lançamento de faturas</h3>
                <form method="GET" action="{{ route('invoices.index') }}" class="relative w-full md:max-w-md">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar por fatura, cliente ou projeto..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-gray-900/5 focus:border-gray-800/30 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200/50">
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600">Referência</th>
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600">Cliente / Projeto</th>
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-center">Vencimento</th>
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Valor</th>
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-center">Status</th>
                                <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoices as $invoice)
                                @php $shortNumber = strtoupper(substr($invoice->invoice_number, -8)); @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-xs font-bold text-gray-500 font-mono">
                                        #{{ $shortNumber }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-600 border border-gray-200/50 shadow-sm group-hover:bg-white transition-colors">
                                                {{ strtoupper(substr($invoice->client->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $invoice->client->name }}
                                                </div>
                                                <div class="text-xs text-blue-600 font-medium">
                                                    {{ $invoice->project->title }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm text-center {{ $invoice->status === 'overdue' ? 'text-rose-600 font-bold' : 'text-gray-600' }}">
                                        {{ $invoice->due_date->format('d/m/Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-bold text-gray-900 text-right tabular-nums">
                                        R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            @php
                                                $statusMap = [
                                                    'paid' => ['label' => 'Paga', 'class' => 'bg-emerald-100 text-emerald-600'],
                                                    'pending' => ['label' => 'Pendente', 'class' => 'bg-yellow-100 text-yellow-700'],
                                                    'overdue' => ['label' => 'Atrasada', 'class' => 'bg-rose-100 text-rose-600'],
                                                ];

                                                // Determina o status a exibir, dando prioridade para "Atrasada" se a data de vencimento já passou
                                                $statusInfo = $statusMap[$invoice->status] ?? ['label' => 'Desconhecido', 'class' => 'bg-gray-100 text-gray-600'];
                                                $isOverdue = $invoice->status === 'pending' && $invoice->due_date->isPast();
                                                if ($isOverdue) {
                                                    $statusInfo = $statusMap['overdue'];
                                                }
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusInfo['class'] }}">
                                                {{ $statusInfo['label'] }}
                                            </span>
                                        </span>
                                    </td>
                                    <td
                                        class="py-4 px-6 text-right space-x-3 text-[11px] font-normal uppercase tracking-tighter">
                                        <a href="{{ route('invoices.edit', $invoice) }}"
                                            class="px-4 py-2 bg-blue-100 text-blue-600 rounded hover:bg-blue-500 hover:text-white transition-colors">Detalhes</a>

                                        <button type="button" @click="
                                                                    showRefundModal = true;
                                                                    invoiceToRefund = '{{ $shortNumber }}';
                                                                    refundRoute = '{{ route('invoices.destroy', $invoice) }}';
                                                                    invoiceConfirmationInput = '';
                                                                "
                                            class="px-4 py-2 bg-rose-100 text-rose-600 rounded hover:bg-rose-500 hover:text-white transition-colors cursor-pointer">
                                            Estornar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center text-gray-500">Nenhum registro encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 pb-6">
                {{ $invoices->links() }}
            </div>
        </div>

        {{-- Modal de Confirmação de Estorno (Blade + Alpine.js) --}}
        <div x-show="showRefundModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showRefundModal = false">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-rose-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 uppercase tracking-tight">Confirmar
                                    Estorno</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Esta ação é irreversível. Para confirmar que deseja estornar a fatura <span
                                            class="font-mono font-bold text-gray-900"
                                            x-text="'#' + invoiceToRefund"></span>, digite o número da fatura abaixo:
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <input type="text" x-model="invoiceConfirmationInput"
                                        class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-rose-500 focus:border-rose-500 sm:text-sm placeholder-gray-400 font-mono"
                                        placeholder="Ex: 5F3D2A1B">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <form :action="refundRoute" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" :disabled="invoiceConfirmationInput !== invoiceToRefund"
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-bold text-white hover:bg-rose-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-30 disabled:cursor-not-allowed uppercase tracking-widest transition-all">
                                Confirmar Estorno
                            </button>
                        </form>
                        <button type="button" @click="showRefundModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest transition-all">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>