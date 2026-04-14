<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidTotal = Invoice::where('status', InvoiceStatus::Paid)->sum('total');
        $outstandingTotal = Invoice::whereIn('status', [InvoiceStatus::Sent, InvoiceStatus::Overdue])->sum('total');
        $draftCount = Invoice::where('status', InvoiceStatus::Draft)->count();

        return [
            Stat::make('Paid Revenue', '$' . number_format($paidTotal, 2))
                ->description('All-time')
                ->color('success'),
            Stat::make('Outstanding', '$' . number_format($outstandingTotal, 2))
                ->description('Sent + Overdue')
                ->color('warning'),
            Stat::make('Draft Invoices', $draftCount)
                ->description('Not yet sent')
                ->color('gray'),
        ];
    }
}
