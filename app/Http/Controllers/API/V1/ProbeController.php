<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Probes\CreateProbeAction;
use App\Actions\Probes\DeleteProbeAction;
use App\Actions\Probes\UpdateProbeAction;
use App\Http\Requests\V1\StoreProbeRequest;
use App\Http\Requests\V1\UpdateProbeRequest;
use App\Http\Resources\V1\ProbeCollection;
use App\Http\Resources\V1\ProbeResource;
use App\Models\Probe;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProbeController
{
    use JsonResponses;
    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->query('size', 5);
        $probes = Probe::sameTeam()->paginate($size);

        return $this->successResponse(new ProbeCollection($probes));
    }

    public function store(StoreProbeRequest $request, CreateProbeAction $createProbesAction): JsonResponse
    {
        $probe = $createProbesAction->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            user: $request->user(),
        );

        return $this->successResponse(new ProbeResource($probe), Response::HTTP_CREATED);
    }

    public function show(Probe $probe): JsonResponse
    {
        return $this->successResponse(new ProbeResource($probe));
    }
    public function update(UpdateProbeRequest $request, Probe $probe, UpdateProbeAction $updateProbesAction): JsonResponse
    {
        $probe = $updateProbesAction->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            probe: $probe,
        );

        return $this->successResponse(new ProbeResource($probe));
    }

    public function destroy(Probe $probe, DeleteProbeAction $deleteProbesAction): JsonResponse
    {
        $deleteProbesAction->handle(
            probe: $probe,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
