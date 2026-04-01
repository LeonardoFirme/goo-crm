<!-- resources/views/dashboard.blade.php -->
<x-app-layout title="Dashboard" subtitle="Visão geral do desempenho financeiro e status dos projetos">

    <div class="py-0">
        <div class="mx-auto max-w-full">

            {{-- Toolbar de Filtros --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Análise de Período</h3>

                <div class="inline-flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
                    @foreach(['7' => '7 Dias', '30' => '30 Dias', '180' => '6 Meses', '365' => '1 Ano'] as $val => $label)
                        <a href="{{ route('dashboard', ['range' => $val, 'search' => $search]) }}"
                            class="px-4 py-1.5 text-[10px] font-bold rounded-lg transition-all {{ $range == $val ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-900' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- KPIs Grid --}}
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-5">

                {{-- Total Recebido --}}
                <div class="rounded-xl border border-emerald-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Total Recebido</p>
                        <i class="fas fa-cash-register text-emerald-500/50 text-sm"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 font-valor">
                        R$ {{ number_format($totalPaid, 2, ',', '.') }}
                    </p>
                </div>

                {{-- Total a receber --}}
                <div class="rounded-xl border border-gray-200/50 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total a receber</p>
                        <i class="fas fa-file-invoice-dollar text-gray-300 text-sm"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-900 font-valor">
                        R$ {{ number_format($totalReceivable, 2, ',', '.') }}
                    </p>
                </div>

                {{-- Inadimplência --}}
                <div
                    class="rounded-xl border p-6 shadow-sm transition-colors {{ $totalOverdue > 0 ? 'border-rose-200 bg-rose-50/50' : 'border-gray-200/50 bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <p
                            class="text-xs font-bold uppercase tracking-wider {{ $totalOverdue > 0 ? 'text-rose-600' : 'text-gray-500' }}">
                            Inadimplência
                        </p>
                        <i
                            class="fas fa-exclamation-triangle {{ $totalOverdue > 0 ? 'text-rose-500/50' : 'text-gray-300' }} text-sm"></i>
                    </div>
                    <p
                        class="text-2xl font-black {{ $totalOverdue > 0 ? 'text-rose-700' : 'text-gray-900 font-valor' }}">
                        R$ {{ number_format($totalOverdue, 2, ',', '.') }}
                    </p>
                </div>

                {{-- Projetos ativos --}}
                <div class="rounded-xl border border-gray-200/50 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Projetos ativos</p>
                        <i class="fas fa-project-diagram text-gray-300 text-sm"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 font-valor">{{ $activeProjectsCount }}</p>
                </div>

                {{-- Clientes ativos --}}
                <div class="rounded-xl border border-gray-200/50 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Clientes ativos</p>
                        <i class="fas fa-users text-gray-300 text-sm"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-900 font-valor">{{ $totalClientsCount }}</p>
                </div>

            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-base font-semibold text-gray-800">Últimas movimentações financeiras</h3>
                        <form method="GET" action="{{ route('dashboard') }}" class="relative w-full max-w-xs">
                            {{-- Manter o range no formulário de busca para não resetar o filtro --}}
                            <input type="hidden" name="range" value="{{ $range }}">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Buscar fatura, cliente ou projeto..."
                                class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200/50 rounded-lg text-xs focus:ring-2 focus:ring-gray-900/5 focus:border-gray-800/30 transition-all">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-search text-[10px]"></i>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200/50 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 bg-gray-50/30">
                                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Fatura</th>
                                        <th class="px-6 py-4 text-sm font-semibold text-gray-600">Cliente</th>
                                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Valor</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($recentInvoices as $invoice)
                                        <tr class="transition-colors hover:bg-gray-50/50">
                                            <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                                #{{ strtoupper(substr($invoice->invoice_number, -8)) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $invoice->client->name }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 text-right font-bold text-gray-900 tabular-nums text-sm font-valor">
                                                R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $statusMap = [
                                                        'pending' => ['label' => 'Pendente', 'class' => 'bg-amber-100 text-amber-700'],
                                                        'paid' => ['label' => 'Pago', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                        'overdue' => ['label' => 'Atrasado', 'class' => 'bg-rose-100 text-rose-700'],
                                                    ];
                                                    $isOverdue = in_array($invoice->status, ['pending', 'overdue']) && $invoice->due_date->isPast();
                                                    $curr = $isOverdue ? $statusMap['overdue'] : ($statusMap[$invoice->status] ?? $statusMap['pending']);
                                                @endphp
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $curr['class'] }}">
                                                    {{ $curr['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center text-xs text-gray-400 font-medium">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-file-invoice text-gray-200 text-4xl mb-4"></i>
                                                    Nenhum registro encontrado para esta busca.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Paginação --}}
                    <div class="mt-4">
                        {{ $recentInvoices->links() }}
                    </div>
                </div>

                {{-- Lista de Clientes Recentes --}}
                <div class="overflow-hidden rounded-xl border border-gray-200/50 bg-white shadow-sm h-fit">
                    <div class="border-b border-gray-100 bg-gray-50/50 p-6">
                        <h3 class="text-base font-bold text-gray-800 tracking-tight">Clientes recentes</h3>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-100 -my-2">
                            @forelse($recentClients as $client)
                                <li class="py-3 flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-600 border border-gray-200/50 shadow-sm group-hover:bg-white transition-colors">
                                            {{ strtoupper(substr($client->name, 0, 2)) }}
                                        </div>

                                        <div>
                                            <p class="text-sm font-bold text-gray-900 line-clamp-1"
                                                title="{{ $client->name }}">
                                                {{ $client->name }}
                                            </p>
                                            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-tighter">
                                                Cadastrado em {{ $client->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    @php
                                        $statusConfig = [
                                            'active' => ['label' => 'Ativo', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                            'inactive' => ['label' => 'Inativo', 'class' => 'bg-gray-100 text-gray-500 border-gray-200/50'],
                                            'lead' => ['label' => 'Lead', 'class' => 'bg-blue-50 text-blue-600 border-blue-100'],
                                        ];
                                        $current = $statusConfig[$client->status] ?? ['label' => $client->status, 'class' => 'bg-gray-50 text-gray-500 border-gray-100'];
                                    @endphp

                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold border {{ $current['class'] }}">
                                        {{ $current['label'] }}
                                    </span>
                                </li>
                            @empty
                                <li class="py-8">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <div
                                            class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fas fa-users-slash text-gray-300 text-xl"></i>
                                        </div>
                                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                                            Nenhum registro encontrado
                                        </p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>