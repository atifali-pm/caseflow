<?php

namespace App\Enums;

enum CaseStatus: string
{
    case Open = 'open';
    case OnHold = 'on_hold';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::OnHold => 'On Hold',
            self::Closed => 'Closed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Open => 'success',
            self::OnHold => 'warning',
            self::Closed => 'gray',
        };
    }
}
