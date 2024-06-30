<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Probes
 *
 * @package App\Models
 * @property int $id
 * @property int $team_id
 * @property int $probe_type_id
 * @property string $name
 * @property string $description
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property-read Team $team
 * @property-read ProbeType $probeType
 * @property-read ProbeMetric[] $metrics
 * @property-read ProbeRule[] $rules
 * @method static Builder sameTeam()
 */
final class Probe extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'probe_type_id',
        'name',
        'description',
    ];

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo
     */
    public function probeType(): BelongsTo
    {
        return $this->belongsTo(ProbeType::class);
    }

    /**
     * @return HasMany
     */
    public function metrics(): HasMany
    {
        return $this->hasMany(ProbeMetric::class, 'probe_id');
    }

    /**
     * @return HasMany
     */
    public function rules(): HasMany
    {
        return $this->hasMany(ProbeRule::class, 'probe_id');
    }

    /**
     * @param Builder $query
     */
    public function scopeSameTeam(Builder $query): void
    {
        $query->where('team_id', auth()->user()->currentTeam->id ?? 0);
    }
}
