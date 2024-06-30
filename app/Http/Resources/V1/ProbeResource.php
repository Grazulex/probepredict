<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Probe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Probe */
final class ProbeResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'probe',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => new DateTimeResource($this->created_at),
            ],
            'relationships' => [
                'probe_type' => [
                    'links' => [
                        'self' => route('api.probe-types.show', ['probe_type' => $this->probe_type_id]),
                        'related' => route('api.probe-types.show', ['probe_type' => $this->probe_type_id]),
                    ],
                    'data' => new ProbeTypeResource($this->probeType),
                ],
                'metrics' => [
                    'links' => [
                        'self' => route('api.probes.metrics', ['probe' => $this->id]),
                        'related' => route('api.probes.metrics', ['probe' => $this->id]),
                    ],
                ],
                'rules' => [
                    'links' => [
                        'self' => route('api.probes.rules', ['probe' => $this->id]),
                        'related' => route('api.probes.rules', ['probe' => $this->id]),
                    ],
                ],
            ],
            'links' => [
                'self' => route('api.probes.show', ['probe' => $this->id]),
            ],
        ];
    }
}
