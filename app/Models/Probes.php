<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Probes extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'probe_type_id',
        'name',
        'description',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function probeType(): BelongsTo
    {
        return $this->belongsTo(ProbeTypes::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ProbeMetrics::class, 'probe_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(ProbeRules::class, 'probe_id');
    }
}
