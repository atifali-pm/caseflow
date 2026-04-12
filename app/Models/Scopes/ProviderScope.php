<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ProviderScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (! $user) {
            $builder->whereRaw('1 = 0');
            return;
        }

        if ($user->isProvider()) {
            $builder->where($model->getTable() . '.provider_id', $user->id);
        }
    }
}
