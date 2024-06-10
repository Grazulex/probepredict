<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProbeMetrics extends Model
{
    use HasFactory;

    protected $fillable = [
        'probe_id',
        'metric_type_id',
        'value',
    ];

    public function probe()
    {
        return $this->belongsTo(Probe::class);
    }

    public function metric_type()
    {
        return $this->belongsTo(MetricTypes::class);
    }
}
