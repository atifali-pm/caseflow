<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CasesByStageChart;
use App\Filament\Widgets\InvoiceStatsWidget;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\TimeLoggedWidget;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Billing';

    protected static ?string $title = 'Reports';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.reports';

    public function getHeaderWidgets(): array
    {
        return [
            InvoiceStatsWidget::class,
            TimeLoggedWidget::class,
        ];
    }

    public function getFooterWidgets(): array
    {
        return [
            RevenueChart::class,
            CasesByStageChart::class,
        ];
    }
}
