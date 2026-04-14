<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue (Last 6 Months)';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->startOfMonth());
        }

        $data = $months->map(function (Carbon $month) {
            return Invoice::where('status', InvoiceStatus::Paid)
                ->whereBetween('paid_at', [$month, $month->copy()->endOfMonth()])
                ->sum('total');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Paid Revenue ($)',
                    'data' => $data->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->map(fn ($m) => $m->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
