<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Case Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('case_record_id')
                            ->relationship('caseRecord', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assigned To')
                            ->options(fn () => User::whereIn('role', ['admin', 'provider'])->pluck('name', 'id'))
                            ->searchable()
                            ->default(fn () => auth()->id()),
                        Forms\Components\Select::make('status')
                            ->options(TaskStatus::class)
                            ->default(TaskStatus::Pending)
                            ->required(),
                        Forms\Components\DatePicker::make('due_date'),
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
                Tables\Columns\TextColumn::make('caseRecord.title')
                    ->label('Case')
                    ->limit(30),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assignee'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (TaskStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('due_date')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::class),
                Tables\Filters\Filter::make('overdue')
                    ->query(fn ($query) => $query->where('due_date', '<', now())->where('status', '!=', TaskStatus::Done)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle')
                    ->label(fn (Task $record) => $record->status === TaskStatus::Done ? 'Reopen' : 'Complete')
                    ->icon(fn (Task $record) => $record->status === TaskStatus::Done ? 'heroicon-o-arrow-path' : 'heroicon-o-check')
                    ->color(fn (Task $record) => $record->status === TaskStatus::Done ? 'gray' : 'success')
                    ->action(function (Task $record) {
                        if ($record->status === TaskStatus::Done) {
                            $record->update(['status' => TaskStatus::Pending, 'completed_at' => null]);
                        } else {
                            $record->update(['status' => TaskStatus::Done, 'completed_at' => now()]);
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
