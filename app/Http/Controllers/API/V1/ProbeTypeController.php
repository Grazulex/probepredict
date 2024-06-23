<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\ProbeTypes\CreateProbeTypeAction;
use App\Actions\ProbeTypes\DeleteProbeTypeAction;
use App\Actions\ProbeTypes\UpdateProbeTypeAction;
use App\Http\Requests\V1\StoreProbeTypeRequest;
use App\Http\Requests\V1\UpdateProbeTypeRequest;
use App\Http\Resources\V1\ProbeTypeResource;
use App\Models\ProbeType;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProbeTypeController
{
    use JsonResponses;

    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->query('size', 5);
        $probe_types = ProbeType::all()->paginate($size);

        return $this->successResponse(ProbeTypeResource::collection($probe_types));
    }

    public function store(StoreProbeTypeRequest $request, CreateProbeTypeAction $createProbeTypeAction): JsonResponse
    {
        $probe_type = $createProbeTypeAction->handle(
            input: $request->only(['name', 'description']),
        );

        return $this->successResponse(new ProbeTypeResource($probe_type), Response::HTTP_CREATED);
    }

    public function show(ProbeType $probeType): JsonResponse
    {
        return $this->successResponse(new ProbeTypeResource($probeType));
    }

    public function update(UpdateProbeTypeRequest $request, ProbeType $probeType, UpdateProbeTypeAction $updateProbeTypeAction): JsonResponse
    {
        $probe_type = $updateProbeTypeAction->handle(
            input: $request->only(['name', 'description']),
            probeType: $probeType,
        );

        return $this->successResponse(new ProbeTypeResource($probe_type));
    }

    public function destroy(ProbeType $probeType, DeleteProbeTypeAction $deleteProbeTypeAction): JsonResponse
    {
        $deleteProbeTypeAction->handle(
            probeType: $probeType,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
