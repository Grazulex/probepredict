<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetricTypes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'created_at',
        'updated_at',
    ];

    public function metrics(): HasMany
    {
        return $this->hasMany(ProbeMetrics::class);
    }
}
