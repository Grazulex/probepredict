<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Probes\CreateProbesAction;
use App\Actions\Probes\DeleteProbesAction;
use App\Actions\Probes\UpdateProbesAction;
use App\Http\Requests\V1\StoreProbeRequest;
use App\Http\Requests\V1\UpdateProbeRequest;
use App\Http\Resources\V1\ProbesResource;
use App\Models\Probes;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProbesController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->query('size', 5);
        $probes = Probes::sameTeam()->paginate($size);

        return $this->sendResponse(
            result: ProbesResource::collection($probes),
            message: 'Probes retrieved successfully.',
            paginator: true,
        );
    }

    public function store(StoreProbeRequest $request, CreateProbesAction $createProbesAction): JsonResponse
    {
        $probe = $createProbesAction->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            user: $request->user(),
        );

        return $this->sendResponse(
            result: new ProbesResource($probe),
            message: 'created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function show(Probes $probes): JsonResponse
    {
        return $this->sendResponse(
            result: new ProbesResource($probes),
            message: 'Probe retrieved successfully.',
        );
    }
    public function update(UpdateProbeRequest $request, Probes $probes, UpdateProbesAction $updateProbesAction): JsonResponse
    {
        $probe = $updateProbesAction->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            probes: $probes,
        );

        return $this->sendResponse(
            result:  new ProbesResource($probes),
            message: 'Probe updated successfully.',
        );
    }

    public function destroy(Probes $probes, DeleteProbesAction $deleteProbesAction): JsonResponse
    {
        $deleteProbesAction->handle(
            probes: $probes,
        );

        return $this->sendResponse(
            result: [],
            message: 'Probe deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
