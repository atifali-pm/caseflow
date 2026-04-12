<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Provider = 'provider';
    case Client = 'client';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Provider => 'Provider',
            self::Client => 'Client',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Provider => 'primary',
            self::Client => 'success',
        };
    }
}
