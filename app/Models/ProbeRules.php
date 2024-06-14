<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProbeMetrics
 * @package App\Models
 * @property int $id
 * @property int $probe_id
 * @property int $metric_type_id
 * @property float $value
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property Probes $probe
 * @property MetricTypes $metricType
 */
final class ProbeRules extends Model
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
