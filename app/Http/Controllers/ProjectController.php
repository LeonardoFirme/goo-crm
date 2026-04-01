<?php
// app/Http/Controllers/ProjectController.php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Exibe a listagem de projetos com suporte a busca e paginação de 20.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        // Eager Loading do relacionamento 'client' para otimização
        $projects = Project::with('client')
            ->latest()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->paginate(20) // Definido para 20 registros por página
            ->withQueryString();

        return view('projects.index', compact('projects', 'search'));
    }

    /**
     * Exibe o formulário de criação, carregando a lista de clientes ativos.
     */
    public function create(): View
    {
        $clients = Client::active()->orderBy('name')->get();

        return view('projects.create', compact('clients'));
    }

    /**
     * Armazena um novo projeto.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Projeto planejado e registrado com sucesso.');
    }

    /**
     * Exibe os detalhes do projeto.
     */
    public function show(Project $project): View
    {
        $project->load('client');

        return view('projects.show', compact('project'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Project $project): View
    {
        $clients = Client::active()->orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Atualiza os dados do projeto.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Cronograma do projeto atualizado com precisão.');
    }

    /**
     * Remove um projeto (Soft Delete).
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Projeto arquivado com sucesso.');
    }

    /**
     * Remove a formatação da máscara para formato decimal.
     */
    private function sanitizeMoney($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $clean = str_replace(['R$', '.', ' '], '', $value);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }
}