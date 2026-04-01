<?php
// app/Http/Controllers/InvoiceController.php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\Client;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Exibe a listagem de faturas com Eager Loading e suporte a busca.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $invoices = Invoice::with(['client', 'project'])
            ->latest('due_date')
            ->when($search, function ($query, $search) {
                return $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            })
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'search'));
    }

    /**
     * Exibe o formulário de criação carregando os dados necessários.
     */
    public function create(): View
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('title')->get();

        return view('invoices.create', compact('clients', 'projects'));
    }

    /**
     * Armazena uma nova fatura e garante a integridade dos IDs.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        Invoice::create($request->validated());

        return redirect()->route('invoices.index')
            ->with('success', 'Fatura gerada e registrada no fluxo financeiro.');
    }

    /**
     * Exibe os detalhes da fatura.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['client', 'project']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Invoice $invoice): View
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::where('client_id', $invoice->client_id)->orderBy('title')->get();

        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Atualiza os dados da fatura.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();

        // Lógica profissional: Se não estiver pago, remove o método de pagamento
        if ($data['status'] !== 'paid') {
            $data['payment_method'] = null;
            $data['paid_at'] = null;
        } else {
            // Se mudou para pago agora e não tinha data, define como hoje
            $data['paid_at'] = $invoice->paid_at ?? now();
        }

        $invoice->update($data);

        return redirect()->route('invoices.index')
            ->with('success', 'Fatura atualizada com sucesso.');
    }

    /**
     * Remove a fatura (Soft Delete).
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Fatura removida do sistema.');
    }

    /**
     * Gera o PDF da fatura para download.
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['client', 'project']);

        // Caminho físico do componente de logo (ajuste se o nome do arquivo for diferente)
        $logoPath = resource_path('views/components/application-logo.blade.php');

        // Lê o conteúdo do SVG e limpa possíveis tags blade
        $logoSvg = "";
        if (file_exists($logoPath)) {
            $logoSvg = file_get_contents($logoPath);
            $logoSvg = preg_replace('/<path\s+[^>]*?fill-current[^>]*?>/i', '<path fill="#1e40af">', $logoSvg); // Troca a classe do tailwind por cor fixa
        }

        if (ob_get_level())
            ob_end_clean();

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'logoSvg'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Helvetica',
            ]);

        return $pdf->download("fatura-{$invoice->invoice_number}.pdf");
    }
}