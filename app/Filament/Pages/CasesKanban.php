<?php

namespace App\Filament\Pages;

use App\Enums\CaseStage;
use App\Models\CaseRecord;
use Filament\Pages\Page;

class CasesKanban extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationGroup = 'Case Management';

    protected static ?string $title = 'Kanban Board';

    protected static ?string $slug = 'cases/kanban';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.cases-kanban';

    public function getColumns(): array
    {
        $columns = [];

        foreach (CaseStage::cases() as $stage) {
            $columns[] = [
                'stage' => $stage,
                'cases' => CaseRecord::with('client')
                    ->where('stage', $stage)
                    ->latest()
                    ->get(),
            ];
        }

        return $columns;
    }

    public function moveCard(int $caseId, string $stage): void
    {
        $case = CaseRecord::find($caseId);
        if ($case) {
            $case->update(['stage' => $stage]);
        }
    }
}
