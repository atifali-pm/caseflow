<?php

namespace App\Filament\Resources;

use App\Enums\CasePriority;
use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Filament\Resources\CaseResource\Pages;
use App\Filament\Resources\CaseResource\RelationManagers;
use App\Models\CaseRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CaseResource extends Resource
{
    protected static ?string $model = CaseRecord::class;

    protected static ?string $modelLabel = 'Case';

    protected static ?string $pluralModelLabel = 'Cases';

    protected static ?string $slug = 'cases';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Case Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Case Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->searchable(['first_name', 'last_name', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(CaseStatus::class)
                            ->default(CaseStatus::Open)
                            ->required(),
                        Forms\Components\Select::make('stage')
                            ->options(CaseStage::class)
                            ->default(CaseStage::Intake)
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->options(CasePriority::class)
                            ->default(CasePriority::Medium)
                            ->required(),
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Dates')
                    ->columns(3)
                    ->schema([
                        Forms\Components\DateTimePicker::make('opened_at')
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date'),
                        Forms\Components\DateTimePicker::make('closed_at')
                            ->visible(fn (Forms\Get $get) => $get('status') === CaseStatus::Closed->value),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label('Client')
                    ->searchable(['client.first_name', 'client.last_name']),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (CaseStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('stage')
                    ->badge()
                    ->color(fn (CaseStage $state) => $state->color()),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (CasePriority $state) => $state->color()),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->color(fn ($state, $record) => $record->tags->firstWhere('name', $state)?->color ?? 'gray')
                    ->separator(','),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('opened_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(CaseStatus::class),
                Tables\Filters\SelectFilter::make('stage')
                    ->options(CaseStage::class),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(CasePriority::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export to CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $filename = 'cases-' . now()->format('Y-m-d-His') . '.csv';
                            $headers = ['ID', 'Title', 'Client', 'Status', 'Stage', 'Priority', 'Opened', 'Due'];

                            return response()->streamDownload(function () use ($records, $headers) {
                                $out = fopen('php://output', 'w');
                                fputcsv($out, $headers);
                                foreach ($records as $record) {
                                    fputcsv($out, [
                                        $record->id,
                                        $record->title,
                                        $record->client?->full_name,
                                        $record->status->label(),
                                        $record->stage->label(),
                                        $record->priority->label(),
                                        $record->opened_at?->format('Y-m-d'),
                                        $record->due_date?->format('Y-m-d'),
                                    ]);
                                }
                                fclose($out);
                            }, $filename);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Client' => $record->client?->full_name ?? '-',
            'Status' => $record->status->label(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TasksRelationManager::class,
            RelationManagers\MilestonesRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
            RelationManagers\NotesRelationManager::class,
            RelationManagers\MessagesRelationManager::class,
            RelationManagers\ActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCases::route('/'),
            'create' => Pages\CreateCase::route('/create'),
            'view' => Pages\ViewCase::route('/{record}'),
            'edit' => Pages\EditCase::route('/{record}/edit'),
        ];
    }
}
