<?php

namespace App\Models;

use App\Models\Concerns\ScopedByProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory, ScopedByProvider;

    protected $fillable = [
        'provider_id',
        'case_record_id',
        'user_id',
        'started_at',
        'duration_minutes',
        'description',
        'billable',
        'hourly_rate',
        'invoice_id',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'billable' => 'boolean',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function caseRecord(): BelongsTo
    {
        return $this->belongsTo(CaseRecord::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getHoursAttribute(): float
    {
        return round($this->duration_minutes / 60, 2);
    }

    public function getAmountAttribute(): float
    {
        return round($this->hours * (float) $this->hourly_rate, 2);
    }
}
