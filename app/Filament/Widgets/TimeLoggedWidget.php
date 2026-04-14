<?php

namespace App\Filament\Widgets;

use App\Models\TimeEntry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TimeLoggedWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $thisWeek = TimeEntry::whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('duration_minutes');
        $thisMonth = TimeEntry::whereBetween('started_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('duration_minutes');
        $billable = TimeEntry::where('billable', true)->sum('duration_minutes');

        return [
            Stat::make('Hours This Week', round($thisWeek / 60, 1) . 'h')
                ->color('info'),
            Stat::make('Hours This Month', round($thisMonth / 60, 1) . 'h')
                ->color('primary'),
            Stat::make('Total Billable', round($billable / 60, 1) . 'h')
                ->color('success'),
        ];
    }
}
