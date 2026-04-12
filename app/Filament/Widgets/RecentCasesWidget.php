<?php

namespace App\Filament\Widgets;

use App\Enums\CaseStatus;
use App\Models\CaseRecord;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCasesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(CaseRecord::query()->latest('updated_at')->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Client'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (CaseStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Last Updated'),
            ])
            ->paginated(false);
    }
}
