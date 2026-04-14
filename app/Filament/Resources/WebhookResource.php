<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookResource\Pages;
use App\Models\Webhook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebhookResource extends Resource
{
    protected static ?string $model = Webhook::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->placeholder('e.g. Zapier integration'),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required()
                    ->placeholder('https://hooks.example.com/incoming'),
                Forms\Components\CheckboxList::make('events')
                    ->required()
                    ->options([
                        'case.created' => 'Case created',
                        'case.updated' => 'Case updated',
                        'case.closed' => 'Case closed',
                        'invoice.paid' => 'Invoice paid',
                        'message.sent' => 'Message sent',
                        'task.completed' => 'Task completed',
                    ])
                    ->columns(2),
                Forms\Components\Toggle::make('active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('url')->limit(40),
                Tables\Columns\TextColumn::make('events')
                    ->getStateUsing(fn ($record) => count($record->events ?? []) . ' events'),
                Tables\Columns\IconColumn::make('active')->boolean(),
                Tables\Columns\TextColumn::make('last_triggered_at')
                    ->since()
                    ->placeholder('Never'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhooks::route('/'),
            'create' => Pages\CreateWebhook::route('/create'),
            'edit' => Pages\EditWebhook::route('/{record}/edit'),
        ];
    }
}
