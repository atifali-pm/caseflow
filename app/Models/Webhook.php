<?php

namespace App\Models;

use App\Models\Concerns\ScopedByProvider;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use ScopedByProvider;

    protected $fillable = [
        'provider_id',
        'name',
        'url',
        'events',
        'secret',
        'active',
        'last_triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'active' => 'boolean',
            'last_triggered_at' => 'datetime',
        ];
    }
}
