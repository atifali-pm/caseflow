<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfController extends Controller
{
    public function show(Invoice $invoice)
    {
        $invoice->load(['lineItems', 'client', 'provider']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->stream("invoice-{$invoice->number}.pdf");
    }
}
