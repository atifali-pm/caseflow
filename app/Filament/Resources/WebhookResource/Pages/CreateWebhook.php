<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateWebhook extends CreateRecord
{
    protected static string $resource = WebhookResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth()->id();
        $data['secret'] = Str::random(40);
        return $data;
    }
}
