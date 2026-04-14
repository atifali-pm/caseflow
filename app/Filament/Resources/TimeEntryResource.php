<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeEntryResource\Pages;
use App\Models\TimeEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimeEntryResource extends Resource
{
    protected static ?string $model = TimeEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Billing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('case_record_id')
                            ->relationship('caseRecord', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('started_at')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('Duration (minutes)')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('hourly_rate')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->default(fn () => auth()->user()->default_hourly_rate ?? 100),
                        Forms\Components\Toggle::make('billable')
                            ->default(true),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('caseRecord.title')
                    ->label('Case')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->getStateUsing(fn ($record) => $record->hours . 'h'),
                Tables\Columns\TextColumn::make('hourly_rate')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('amount')
                    ->getStateUsing(fn ($record) => '$' . number_format($record->amount, 2))
                    ->label('Amount'),
                Tables\Columns\IconColumn::make('billable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('invoice_id')
                    ->label('Invoiced')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->invoice_id !== null),
            ])
            ->defaultSort('started_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('billable'),
                Tables\Filters\TernaryFilter::make('invoiced')
                    ->queries(
                        true: fn ($q) => $q->whereNotNull('invoice_id'),
                        false: fn ($q) => $q->whereNull('invoice_id'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimeEntries::route('/'),
            'create' => Pages\CreateTimeEntry::route('/create'),
            'edit' => Pages\EditTimeEntry::route('/{record}/edit'),
        ];
    }
}
