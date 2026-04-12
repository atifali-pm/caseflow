<?php

namespace App\Models\Concerns;

use App\Models\Scopes\ProviderScope;

trait ScopedByProvider
{
    public static function bootScopedByProvider(): void
    {
        static::addGlobalScope(new ProviderScope);
    }
}
