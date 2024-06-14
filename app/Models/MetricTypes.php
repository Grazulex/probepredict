<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
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
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property ProbeMetrics[] $metrics
 */
final class MetricTypes extends Model
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
