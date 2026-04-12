<?php

namespace App\Filament\Widgets;

use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Models\CaseRecord;
use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CaseStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open Cases', CaseRecord::where('status', CaseStatus::Open)->count())
                ->description('Currently active')
                ->color('success'),
            Stat::make('In Review', CaseRecord::where('stage', CaseStage::Review)->count())
                ->description('Awaiting review')
                ->color('warning'),
            Stat::make('Overdue', CaseRecord::where('due_date', '<', now())->where('status', '!=', CaseStatus::Closed)->count())
                ->description('Past due date')
                ->color('danger'),
            Stat::make('Total Clients', Client::count())
                ->description('All clients')
                ->color('primary'),
        ];
    }
}
