<!-- resources/views/clients/index.blade.php -->
<x-app-layout title="Clientes" subtitle="Gerencie seus clientes e leads cadastrados">
    <x-slot name="header">
        <div class="flex justify-between items-center px-6">
            <a href="{{ route('clients.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-lg font-bold text-xs text-white shadow-sm hover:bg-blue-950 transition ease-in-out duration-150">
                Novo cliente
            </a>
        </div>
    </x-slot>

    <div class="py-0" x-data="{
        showDeleteModal: false,
        clientName: '',
        confirmationInput: '',
        deleteRoute: ''
    }">
        <div class="max-w-full mx-auto">
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-base font-semibold text-blue-800">Lista de clientes cadastrados</h3>
                <form method="GET" action="{{ route('clients.index') }}" class="relative w-full md:max-w-md">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar por nome, e-mail ou documento..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200/50 rounded-lg text-sm focus:ring-2 focus:ring-gray-900/5 focus:border-gray-800/30 transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200/50">
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600">Cliente/E-mail</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600">Documento</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-center">Status</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-center">Observação</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-600 border border-gray-200/50 shadow-sm group-hover:bg-white transition-colors">
                                            {{ strtoupper(substr($client->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $client->name }}</div>
                                            <div class="text-xs text-gray-500 font-medium">{{ $client->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-gray-600 font-mono">{{ $client->tax_id }}</td>
                                <td class="py-4 px-6 text-center">
                                    @php
                                        $statusMap = [
                                            'active' => ['label' => 'Ativo', 'class' => 'bg-emerald-100 text-emerald-600'],
                                            'inactive' => ['label' => 'Inativo', 'class' => 'bg-gray-100 text-gray-600'],
                                            'lead' => ['label' => 'Lead', 'class' => 'bg-amber-100 text-amber-700'],
                                        ];

                                        $statusInfo = $statusMap[$client->status] ?? [
                                            'label' => 'Desconhecido',
                                            'class' => 'bg-gray-100 text-gray-400'
                                        ];
                                    @endphp

                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    {{ Str::limit($client->notes, 32) }}
                                </td>
                                <td class="py-4 px-6 text-right space-x-3 text-xs font-normal uppercase">
                                    <a href="{{ route('clients.edit', $client) }}"
                                        class="px-4 py-2 bg-blue-100 text-blue-600 rounded hover:bg-blue-500 hover:text-white transition-colors">Editar</a>
                                    <button type="button"
                                        @click="showDeleteModal = true; clientName = '{{ $client->name }}'; deleteRoute = '{{ route('clients.destroy', $client) }}'; confirmationInput = '';"
                                        class="px-4 py-2 bg-rose-100 text-rose-600 rounded hover:bg-rose-500 hover:text-white transition-colors cursor-pointer">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-gray-500">Nenhum cliente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6 pb-6">{{ $clients->links() }}</div>
        </div>

        {{-- Modal Protegido com x-cloak --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showDeleteModal = false">
                    <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-user-times text-rose-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight">Excluir Cliente
                                </h3>
                                <div class="mt-3 text-sm text-gray-500">
                                    <p>Esta ação removerá todos os dados vinculados ao cliente <span
                                            class="text-blue-600 font-black" x-text="clientName"></span>.</p>
                                    <p class="mt-4">Digite o nome do cliente para confirmar:</p>
                                </div>
                                <input type="text" x-model="confirmationInput"
                                    class="mt-4 block w-full border-gray-200 rounded-xl shadow-sm focus:ring-rose-500 focus:border-rose-500 sm:text-sm"
                                    placeholder="Digite aqui...">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                        <form :action="deleteRoute" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" :disabled="confirmationInput !== clientName"
                                class="w-full inline-flex justify-center rounded-xl px-6 py-2.5 bg-rose-600 text-xs font-bold text-white uppercase tracking-widest disabled:opacity-30 transition-all">Excluir</button>
                        </form>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-gray-300 px-6 py-2.5 bg-white text-xs font-bold text-gray-700 uppercase tracking-widest">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>