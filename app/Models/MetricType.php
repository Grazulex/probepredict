<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MetricTypes
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $unit
 * @property ProbeMetric[] $metrics
 */
final class MetricType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
    ];

    public function metrics(): HasMany
    {
        return $this->hasMany(ProbeMetric::class);
    }
}
