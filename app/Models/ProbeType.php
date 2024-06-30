<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ProbeTypes
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Probe[] $probes
 */
final class ProbeType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];


    public function probes(): HasMany
    {
        return $this->hasMany(Probe::class);
    }

}
