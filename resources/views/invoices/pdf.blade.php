<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #1e293b;
            line-height: 1.5;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
        }

        .status {
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            background: #f1f5f9;
            color: #475569;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background: #f8fafc;
            text-align: left;
            font-size: 10px;
            color: #64748b;
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 12px 10px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px dashed #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: middle;">
                    <img src="/logo.png" alt="GooCRM Logo" style="height: 40px;">
                    <div style="font-size: 10px; color: #64748b;">
                        GooCRM - ERP Simples<br>
                        {{ date('d/m/Y') }}
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <span class="status {{ $invoice->status === 'paid' ? 'status-paid' : '' }}">
                        {{ $invoice->status === 'paid' ? 'Liquidada' : 'Pendente' }}
                    </span>
                    <div style="margin-top: 8px; font-weight: bold; font-family: 'Courier', monospace;">
                        #{{ $invoice->invoice_number }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table style="width: 100%; margin-bottom: 40px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-size: 9px; color: #94a3b8; font-weight: bold; margin-bottom: 5px;">DESTINATÁRIO</div>
                <div style="font-weight: bold; font-size: 14px;">{{ $invoice->client->name }}</div>
                <div style="font-size: 11px; color: #64748b;">{{ $invoice->client->tax_id }}</div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <div style="font-size: 9px; color: #94a3b8; font-weight: bold; margin-bottom: 5px;">EMISSOR</div>
                <div style="font-weight: bold; font-size: 12px;">{{ auth()->user()->name }}</div>
                <div style="font-size: 11px; color: #64748b;">{{ auth()->user()->email }}</div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Descrição do Serviço / Projeto</th>
                <th style="width: 100px;">Vencimento</th>
                <th style="width: 120px; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold;">{{ $invoice->project->title }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                        {{ $invoice->notes ?? 'Nenhuma observação adicional.' }}
                    </div>
                </td>
                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                <td style="text-align: right; font-weight: bold; font-size: 14px;">
                    R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($invoice->status === 'paid')
        <div style="margin-top: 30px; padding: 15px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">
            <div style="font-size: 9px; color: #166534; font-weight: bold;">COMPROVANTE DE QUITAÇÃO</div>
            <div style="font-size: 12px; color: #166534;">
                Recebido via <strong>{{ $invoice->payment_method }}</strong> em
                {{ $invoice->updated_at->format('d/m/Y \à\s H:i') }}
            </div>
        </div>
    @endif

    <div class="footer">
        Este documento é uma representação eletrônica de fatura gerada em {{ date('d/m/Y H:i') }}.<br>
        GooCRM - Gestão Inteligente para Negócios.
    </div>
</body>

</html>