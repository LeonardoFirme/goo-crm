<!-- resources/views/projects/index.blade.php -->
<x-app-layout title="Projetos" subtitle="Gerencie os projetos, prazos e orçamentos da sua agência">
    <x-slot name="header">
        <div class="flex justify-between items-center px-6">
            <a href="{{ route('projects.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-lg font-bold text-xs text-white shadow-sm hover:bg-blue-950 transition ease-in-out duration-150">
                Novo projeto
            </a>
        </div>
    </x-slot>

    <div class="py-0" x-data="{
        showArchiveModal: false,
        projectName: '',
        confirmationInput: '',
        archiveRoute: ''
    }">
        <div class="max-w-full mx-auto sm:px-6">
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-sm text-emerald-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-base font-semibold text-blue-800">Lista de projetos</h3>
                <form method="GET" action="{{ route('projects.index') }}" class="relative w-full md:max-w-md">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Buscar por projeto ou cliente..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200/50 rounded-lg text-sm focus:ring-2 focus:ring-gray-900/5">
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200/50">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200/50">
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600">Projeto</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Orçamento</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Data início</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Data entrega
                            </th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Status</th>
                            <th class="py-4 px-6 text-sm font-semibold text-gray-600 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($projects as $project)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-600 border border-gray-200/50 shadow-sm group-hover:bg-white transition-colors">
                                            {{ strtoupper(substr($project->client->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $project->title }}</div>
                                            <div class="text-xs text-blue-600">{{ $project->client->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm font-bold text-right tabular-nums">R$
                                    {{ number_format($project->budget, 2, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 text-right">
                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 text-right">
                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d/m/Y') }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 text-right">
                                    @php
                                        $statusMap = [
                                            'planning' => ['Planejamento', 'class' => 'bg-blue-100 text-blue-600'],
                                            'in_progress' => ['Em andamento', 'class' => 'bg-green-100 text-green-600'],
                                            'on_hold' => ['Em espera', 'class' => 'bg-amber-100 text-amber-600'],
                                            'completed' => ['Concluído', 'class' => 'bg-emerald-100 text-emerald-600'],
                                            'cancelled' => ['Cancelado', 'class' => 'bg-red-100 text-red-600']
                                        ];
                                        $statusInfo = $statusMap[$project->status] ?? ['Desconecido', 'class' => 'bg-gray-100 text-gray-600'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusInfo['class'] }}">
                                        {{ $statusInfo[0] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2 text-xs font-normal uppercase">
                                    <a href="{{ route('projects.edit', $project) }}"
                                        class="px-4 py-2 bg-blue-100 text-blue-600 rounded hover:bg-blue-500 hover:text-white transition-colors">Editar</a>
                                    <button type="button"
                                        @click="showArchiveModal = true; projectName = '{{ addslashes($project->title) }}'; archiveRoute = '{{ route('projects.destroy', $project) }}'; confirmationInput = '';"
                                        class="px-4 py-2 bg-rose-100 text-rose-600 rounded hover:bg-rose-500 hover:text-white transition-colors cursor-pointer">Arquivar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-16 text-center text-gray-500">Vazio.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6 pb-6">{{ $projects->links() }}</div>
        </div>

        {{-- Modal de Arquivamento --}}
        <template x-if="showArchiveModal">
            <div x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm" @click="showArchiveModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Arquivar Projeto</h3>
                            <p class="mt-3 text-sm text-gray-500">Digite <span class="text-blue-600 font-black"
                                    x-text="projectName"></span> para confirmar:</p>
                            <input type="text" x-model="confirmationInput"
                                class="mt-4 block w-full border-gray-200 rounded-xl shadow-sm"
                                placeholder="Nome do projeto...">
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                            <form :action="archiveRoute" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" :disabled="confirmationInput !== projectName"
                                    class="w-full rounded-xl px-6 py-2.5 bg-amber-600 text-xs font-bold text-white uppercase disabled:opacity-30">Arquivar</button>
                            </form>
                            <button type="button" @click="showArchiveModal = false"
                                class="mt-3 sm:mt-0 w-full border border-gray-300 rounded-xl px-6 py-2.5 bg-white text-xs font-bold text-gray-700 uppercase">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>