<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\ProbeMetrics;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeMetrics */
final class ProbeMetricsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'metric_type' => new MetricTypesResource($this->metric_type),
            'value' => $this->value,
            'created_at' => new DateTimeResource($this->created_at),
        ];
    }
}
