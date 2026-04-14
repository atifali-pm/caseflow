<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Invoice $invoice */
        $invoice = $this->record;
        $invoice->recalculate();
    }
}
