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

final class ProbeTypes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'enum',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'enum' => ProbeTypeEnum::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function probes(): HasMany
    {
        return $this->hasMany(Probes::class);
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
