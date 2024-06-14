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
 * @package App\Models
 * @property int $id
 * @property int $team_id
 * @property int $probe_type_id
 * @property string $name
 * @property string $description
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property Team $team
 * @property ProbeTypes $probeType
 * @property ProbeMetrics[] $metrics
 * @property ProbeRules[] $rules
 * @method static Builder sameTeam()
 */
final class Probes extends Model
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

    public function scopeSameTeam(Builder $query): void
    {
        $query->where('team_id', auth()->user()->currentTeam->id);
    }
}
