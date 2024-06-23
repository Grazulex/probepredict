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

/**
 * Class ProbeController
 *
 * @package App\Http\Controllers\API\V1
 *
 * This controller handles all the HTTP requests related to Probes.
 */
final class ProbeController
{
    use JsonResponses;

    /**
     * Display a listing of the probes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->query('size', 5);
        $probes = Probe::sameTeam()->paginate($size);

        return $this->successResponse(new ProbeCollection($probes));
    }

    /**
     * Store a newly created probe in storage.
     *
     * @param StoreProbeRequest $request
     * @param CreateProbeAction $createProbesAction
     * @return JsonResponse
     */
    public function store(StoreProbeRequest $request, CreateProbeAction $createProbesAction): JsonResponse
    {
        $probe = $createProbesAction->handle(
            input: $this->getProbeAttributes($request),
            user: $request->user(),
        );

        return $this->successResponse(new ProbeResource($probe), Response::HTTP_CREATED);
    }

    /**
     * Display the specified probe.
     *
     * @param Probe $probe
     * @return JsonResponse
     */
    public function show(Probe $probe): JsonResponse
    {
        return $this->successResponse(new ProbeResource($probe));
    }

    /**
     * Update the specified probe in storage.
     *
     * @param UpdateProbeRequest $request
     * @param Probe $probe
     * @param UpdateProbeAction $updateProbesAction
     * @return JsonResponse
     */
    public function update(UpdateProbeRequest $request, Probe $probe, UpdateProbeAction $updateProbesAction): JsonResponse
    {
        $probe = $updateProbesAction->handle(
            input: $this->getProbeAttributes($request),
            probe: $probe,
        );

        return $this->successResponse(new ProbeResource($probe));
    }

    /**
     * Remove the specified probe from storage.
     *
     * @param Probe $probe
     * @param DeleteProbeAction $deleteProbesAction
     * @return JsonResponse
     */
    public function destroy(Probe $probe, DeleteProbeAction $deleteProbesAction): JsonResponse
    {
        $deleteProbesAction->handle(
            probe: $probe,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param  Request  $request
     * @return array
     */
    private function getProbeAttributes(Request $request): array
    {
        return $request->only(['name', 'description', 'probe_type_id']);
    }
}
