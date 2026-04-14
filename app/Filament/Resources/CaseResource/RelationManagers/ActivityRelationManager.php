<?php

namespace App\Filament\Resources\CaseResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Activity';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event')
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('By')
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('changes')
                    ->getStateUsing(fn ($record) => $record->changes ? collect($record->changes)->keys()->join(', ') : '-')
                    ->label('Changed Fields'),
                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
