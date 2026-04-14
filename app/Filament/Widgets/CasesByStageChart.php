<?php

namespace App\Filament\Widgets;

use App\Enums\CaseStage;
use App\Models\CaseRecord;
use Filament\Widgets\ChartWidget;

class CasesByStageChart extends ChartWidget
{
    protected static ?string $heading = 'Cases by Stage';

    protected function getData(): array
    {
        $stages = CaseStage::cases();

        $counts = collect($stages)->map(function ($stage) {
            return CaseRecord::where('stage', $stage)->count();
        });

        return [
            'datasets' => [
                [
                    'data' => $counts->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#6b7280'],
                ],
            ],
            'labels' => collect($stages)->map(fn ($s) => $s->label())->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
