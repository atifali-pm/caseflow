<?php

namespace App\Enums;

enum Plan: string
{
    case Free = 'free';
    case Pro = 'pro';
    case Enterprise = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Pro => 'Pro',
            self::Enterprise => 'Enterprise',
        };
    }

    public function price(): int
    {
        return match ($this) {
            self::Free => 0,
            self::Pro => 2900,
            self::Enterprise => 9900,
        };
    }

    public function priceLabel(): string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Pro => '$29/mo',
            self::Enterprise => '$99/mo',
        };
    }

    public function caseLimit(): ?int
    {
        return match ($this) {
            self::Free => 5,
            self::Pro => 50,
            self::Enterprise => null,
        };
    }

    public function stripePriceId(): ?string
    {
        return match ($this) {
            self::Free => null,
            self::Pro => config('services.stripe.price_pro'),
            self::Enterprise => config('services.stripe.price_enterprise'),
        };
    }

    public function features(): array
    {
        return match ($this) {
            self::Free => [
                'Up to 5 cases',
                'Basic case management',
                'Client portal',
                'Document uploads',
            ],
            self::Pro => [
                'Up to 50 cases',
                'Advanced case management',
                'Client portal',
                'Document uploads',
                'Priority support',
            ],
            self::Enterprise => [
                'Unlimited cases',
                'Full case management suite',
                'Client portal',
                'Document uploads',
                'Priority support',
                'Custom integrations',
            ],
        };
    }
}
