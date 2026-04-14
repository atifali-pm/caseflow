<?php

namespace App\Models;

use App\Models\Concerns\ScopedByProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory, ScopedByProvider;

    protected $fillable = ['provider_id', 'name', 'color'];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function cases(): MorphToMany
    {
        return $this->morphedByMany(CaseRecord::class, 'taggable');
    }
}
