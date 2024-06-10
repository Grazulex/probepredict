<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Probes extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'probe_type_id',
        'name',
        'description',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function probeType()
    {
        return $this->belongsTo(ProbeTypes::class);
    }

    public function metrics()
    {
        return $this->hasMany(ProbeMetrics::class, 'probe_id');
    }
}
