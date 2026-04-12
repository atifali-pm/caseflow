<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Pages\Auth\Register;

class ProviderRegistration extends Register
{
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['role'] = UserRole::Provider->value;

        return $data;
    }
}
