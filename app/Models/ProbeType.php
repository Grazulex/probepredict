<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProbeTypeEnum;
use App\Strategies\BatteryStrategy;
use App\Strategies\CalculationStrategy;
use App\Strategies\CarStrategy;
use App\Strategies\EnvironmentalStrategy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * Class ProbeTypes
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property ProbeTypeEnum $enum
 * @property Probe[] $probes
 */
final class ProbeType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'enum',
    ];

    protected $casts = [
        'enum' => ProbeTypeEnum::class,
    ];

    public function probes(): HasMany
    {
        return $this->hasMany(Probe::class);
    }

    public function getCalculationStrategy(): CalculationStrategy
    {
        return match ($this->enum) {
            ProbeTypeEnum::ENVIRONMENT => new EnvironmentalStrategy(),
            ProbeTypeEnum::CAR => new CarStrategy(),
            ProbeTypeEnum::BATTERY => new BatteryStrategy(),
            default => throw new RuntimeException('Unknown probe type'),
        };
    }
}
