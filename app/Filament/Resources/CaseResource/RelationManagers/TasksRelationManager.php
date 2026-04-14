<?php

namespace App\Filament\Resources\CaseResource\RelationManagers;

use App\Enums\TaskStatus;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('assigned_to')
                    ->label('Assignee')
                    ->options(fn () => User::whereIn('role', ['admin', 'provider'])->pluck('name', 'id'))
                    ->default(fn () => auth()->id()),
                Forms\Components\Select::make('status')
                    ->options(TaskStatus::class)
                    ->default(TaskStatus::Pending)
                    ->required(),
                Forms\Components\DatePicker::make('due_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('assignee.name')->label('Assignee'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (TaskStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('due_date')->date(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data) => [
                        ...$data,
                        'provider_id' => auth()->id(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
