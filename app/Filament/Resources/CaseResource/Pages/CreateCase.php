<?php

namespace App\Filament\Resources\CaseResource\Pages;

use App\Filament\Resources\CaseResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCase extends CreateRecord
{
    protected static string $resource = CaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provider_id'] = auth()->id();

        return $data;
    }

    protected function beforeCreate(): void
    {
        $user = auth()->user();

        if (! $user->canCreateCase()) {
            $plan = $user->currentPlan();

            Notification::make()
                ->danger()
                ->title('Case limit reached')
                ->body("Your {$plan->label()} plan allows up to {$plan->caseLimit()} cases. Upgrade to create more.")
                ->persistent()
                ->send();

            $this->halt();
        }
    }
}
