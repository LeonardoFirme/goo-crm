<?php
// app/Http/Controllers/ClientController.php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Exibe a listagem de clientes com busca e paginação de 20.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $clients = Client::latest()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('tax_id', 'like', "%{$search}%");
                });
            })
            ->paginate(20) // Definido para 20 registros por página
            ->withQueryString(); // Mantém o parâmetro 'search' na paginação

        return view('clients.index', compact('clients', 'search'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Armazena um novo cliente.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso absoluta.');
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Atualiza os dados do cliente.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', 'Dados do cliente atualizados com precisão.');
    }

    /**
     * Remove um cliente (Soft Delete).
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente removido do sistema.');
    }
}