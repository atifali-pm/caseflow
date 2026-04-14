<?php

namespace App\Observers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Notifications\InvoicePaidNotification;

class InvoiceObserver
{
    public function updated(Invoice $invoice): void
    {
        if ($invoice->wasChanged('status') && $invoice->status === InvoiceStatus::Paid) {
            $invoice->provider?->notify(new InvoicePaidNotification($invoice));
        }
    }
}
