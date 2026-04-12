<?php

namespace App\Enums;

enum CaseStage: string
{
    case Intake = 'intake';
    case Active = 'active';
    case Review = 'review';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Intake => 'Intake',
            self::Active => 'Active',
            self::Review => 'Review',
            self::Closed => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Intake => 'info',
            self::Active => 'success',
            self::Review => 'warning',
            self::Closed => 'gray',
        };
    }
}
