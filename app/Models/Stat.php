<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Stat
 * @package App\Models
 * @property int $id
 * @property int $metric_type_id
 * @property int $probe_id
 * @property float $avg_increase_minute
 * @property float $avg_decrease_minute
 * @property float $min
 * @property float $max
 * @property DateTime $started_at
 * @property DateTime $ended_at
 * @property MetricType $metricType
 * @property Probe $probe
 */
class Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_type_id',
        'probe_id',
        'started_at',
        'ended_at',
        'avg_increase_minute',
        'avg_decrease_minute',
        'min',
        'max',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function metricType(): BelongsTo
    {
        return $this->belongsTo(MetricType::class);
    }

    public function probe(): BelongsTo
    {
        return $this->belongsTo(Probe::class);
    }
}
