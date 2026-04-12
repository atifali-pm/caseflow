<?php

namespace App\Models;

use App\Enums\CaseStage;
use App\Enums\CaseStatus;
use App\Models\Concerns\ScopedByProvider;
use Database\Factories\CaseRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CaseRecord extends Model
{
    /** @use HasFactory<CaseRecordFactory> */
    use HasFactory, ScopedByProvider;

    protected $table = 'case_records';

    protected $fillable = [
        'provider_id',
        'client_id',
        'title',
        'description',
        'status',
        'stage',
        'opened_at',
        'closed_at',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => CaseStatus::class,
            'stage' => CaseStage::class,
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'due_date' => 'date',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(CaseMilestone::class)->orderBy('sort_order');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
