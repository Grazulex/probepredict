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

/**
 * Class ProbeTypeController
 *
 * @package App\Http\Controllers\API\V1
 *
 * This controller handles all the HTTP requests related to ProbeTypes.
 */
final class ProbeTypeController
{
    use JsonResponses;

    /**
     * Display a listing of the probe types.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successResponse(ProbeTypeResource::collection(ProbeType::all()));
    }

    /**
     * Store a newly created probe type in storage.
     *
     * @param StoreProbeTypeRequest $request
     * @param CreateProbeTypeAction $createProbeTypeAction
     * @return JsonResponse
     */
    public function store(StoreProbeTypeRequest $request, CreateProbeTypeAction $createProbeTypeAction): JsonResponse
    {
        $probe_type = $createProbeTypeAction->handle(
            input: $this->getTypeAttributes($request),
        );

        return $this->successResponse(new ProbeTypeResource($probe_type), Response::HTTP_CREATED);
    }

    /**
     * Display the specified probe type.
     *
     * @param ProbeType $probeType
     * @return JsonResponse
     */
    public function show(ProbeType $probeType): JsonResponse
    {
        return $this->successResponse(new ProbeTypeResource($probeType));
    }

    /**
     * Update the specified probe type in storage.
     *
     * @param UpdateProbeTypeRequest $request
     * @param ProbeType $probeType
     * @param UpdateProbeTypeAction $updateProbeTypeAction
     * @return JsonResponse
     */
    public function update(UpdateProbeTypeRequest $request, ProbeType $probeType, UpdateProbeTypeAction $updateProbeTypeAction): JsonResponse
    {
        $probe_type = $updateProbeTypeAction->handle(
            input: $this->getTypeAttributes($request),
            probeType: $probeType,
        );

        return $this->successResponse(new ProbeTypeResource($probe_type));
    }

    /**
     * Remove the specified probe type from storage.
     *
     * @param ProbeType $probeType
     * @param DeleteProbeTypeAction $deleteProbeTypeAction
     * @return JsonResponse
     */
    public function destroy(ProbeType $probeType, DeleteProbeTypeAction $deleteProbeTypeAction): JsonResponse
    {
        $deleteProbeTypeAction->handle(
            probeType: $probeType,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    private function getTypeAttributes(Request $request): array
    {
        return $request->only(['name', 'description']);
    }
}
