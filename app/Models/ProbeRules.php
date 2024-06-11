<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProbeRules extends Model
{
    use HasFactory;

    protected $fillable = [
        'probe_id',
        'metric_type_id',
        'operator',
        'condition',
        'estimation',
    ];

    protected $casts = [
        'condition' => 'float',
        'estimation' => 'datetime',
    ];

    public function probe(): BelongsTo
    {
        return $this->belongsTo(Probes::class);
    }

    public function metric_type(): BelongsTo
    {
        return $this->belongsTo(MetricTypes::class);
    }
}
