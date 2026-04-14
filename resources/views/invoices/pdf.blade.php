<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: bold; color: #f59e0b; }
        .invoice-meta { text-align: right; }
        .invoice-meta h1 { margin: 0; font-size: 28px; color: #333; }
        .invoice-meta p { margin: 4px 0; }
        .parties { display: flex; justify-content: space-between; margin: 30px 0; }
        .party { width: 45%; }
        .party h3 { font-size: 11px; text-transform: uppercase; color: #999; margin: 0 0 8px 0; }
        .party p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        thead { background: #f5f5f5; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { font-size: 11px; text-transform: uppercase; color: #666; }
        .text-right { text-align: right; }
        .totals { margin-top: 20px; width: 300px; margin-left: auto; }
        .totals table { margin: 0; }
        .totals td { border: none; padding: 5px 10px; }
        .totals .total-row { font-size: 16px; font-weight: bold; border-top: 2px solid #333; }
        .notes { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 11px; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 10px; text-transform: uppercase; font-weight: bold; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">CaseFlow</div>
            <p style="margin-top: 4px; color: #666;">Case Management Platform</p>
        </div>
        <div class="invoice-meta">
            <h1>INVOICE</h1>
            <p><strong>{{ $invoice->number }}</strong></p>
            <p>Issued: {{ $invoice->issued_at->format('M j, Y') }}</p>
            @if ($invoice->due_at)
                <p>Due: {{ $invoice->due_at->format('M j, Y') }}</p>
            @endif
            <span class="status status-{{ $invoice->status->value }}">{{ $invoice->status->label() }}</span>
        </div>
    </div>

    <div class="parties">
        <div class="party">
            <h3>From</h3>
            <p><strong>{{ $invoice->provider->name }}</strong></p>
            <p>{{ $invoice->provider->email }}</p>
        </div>
        <div class="party">
            <h3>Bill To</h3>
            <p><strong>{{ $invoice->client->full_name }}</strong></p>
            <p>{{ $invoice->client->email }}</p>
            @if ($invoice->client->phone)
                <p>{{ $invoice->client->phone }}</p>
            @endif
            @if ($invoice->client->address)
                <p>{{ $invoice->client->address }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->lineItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                    <td class="text-right">${{ number_format($item->rate, 2) }}</td>
                    <td class="text-right">${{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td class="text-right">${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if ($invoice->notes)
        <div class="notes">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif
</body>
</html>
