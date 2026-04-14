<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class ApiTokens extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'API Tokens';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.api-tokens';

    public ?string $newToken = null;

    public function getTokens()
    {
        return auth()->user()->tokens()->latest()->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Token')
                ->form([
                    TextInput::make('name')
                        ->required()
                        ->placeholder('e.g. Mobile App, Zapier'),
                ])
                ->action(function (array $data) {
                    $token = auth()->user()->createToken($data['name']);
                    $this->newToken = $token->plainTextToken;

                    Notification::make()
                        ->success()
                        ->title('Token created')
                        ->body('Copy it now, it will not be shown again.')
                        ->send();
                }),
        ];
    }

    public function deleteToken(int $id): void
    {
        auth()->user()->tokens()->where('id', $id)->delete();

        Notification::make()
            ->success()
            ->title('Token revoked')
            ->send();
    }
}
