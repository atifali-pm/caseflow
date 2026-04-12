<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CasesRelationManager extends RelationManager
{
    protected static string $relationship = 'cases';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (CaseStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('stage')
                    ->badge()
                    ->color(fn (CaseStage $state) => $state->color()),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn ($record) => route('filament.admin.resources.cases.edit', $record))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
