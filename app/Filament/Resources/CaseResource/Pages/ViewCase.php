<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCase extends ViewRecord
{
    protected static string $resource = CaseResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Case Details')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('title'),
                        Infolists\Components\TextEntry::make('client.full_name')
                            ->label('Client'),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => $state->color()),
                        Infolists\Components\TextEntry::make('stage')
                            ->badge()
                            ->color(fn ($state) => $state->color()),
                    ]),
                Infolists\Components\Section::make('Dates')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('opened_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('due_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('closed_at')
                            ->dateTime()
                            ->placeholder('N/A'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
