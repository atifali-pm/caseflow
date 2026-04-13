<?php

namespace App\Models;

use App\Models\Concerns\ScopedByProvider;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory, Notifiable, ScopedByProvider;

    protected $fillable = [
        'provider_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'notes',
        'invitation_token',
    ];

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CaseRecord::class);
    }
}
