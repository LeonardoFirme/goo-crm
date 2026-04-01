<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $today = Carbon::today()->format('Y-m-d');
        $search = $request->query('search');
        $range = $request->query('range', '30'); // Padrão 30 dias

        // Define a data de início para os filtros financeiros
        $startDate = match ($range) {
            '7' => Carbon::now()->subDays(7),
            '180' => Carbon::now()->subMonths(6),
            '365' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };

        // Total Recebido: Filtrado por período (updated_at pois é quando foi pago)
        $totalPaid = (float) Invoice::where('status', 'paid')
            ->whereBetween('updated_at', [$startDate, Carbon::now()])
            ->sum('amount');

        // Inadimplência: Faturas vencidas dentro do período de vencimento selecionado
        $totalOverdue = (float) Invoice::whereIn('status', ['pending', 'overdue'])
            ->whereDate('due_date', '<', $today)
            ->whereDate('due_date', '>=', $startDate->format('Y-m-d'))
            ->sum('amount');

        // Total a receber: Faturas pendentes no futuro (Próximos X dias)
        $totalReceivable = (float) Invoice::where('status', 'pending')
            ->whereDate('due_date', '>=', $today)
            ->whereDate('due_date', '<=', Carbon::now()->addDays((int) $range)->format('Y-m-d'))
            ->sum('amount');

        $activeProjectsCount = Project::where('status', 'in_progress')->count();
        $totalClientsCount = Client::where('status', 'active')->count();

        $recentInvoices = Invoice::with(['client', 'project'])
            ->when($search, function ($query, $search) {
                return $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $recentClients = Client::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalPaid',
            'totalReceivable',
            'totalOverdue',
            'activeProjectsCount',
            'totalClientsCount',
            'recentInvoices',
            'recentClients',
            'search',
            'range'
        ));
    }
}