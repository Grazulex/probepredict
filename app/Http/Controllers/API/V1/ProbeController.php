<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Probes\CreateProbeAction;
use App\Actions\Probes\DeleteProbeAction;
use App\Actions\Probes\UpdateProbeAction;
use App\Http\Requests\V1\StoreProbeRequest;
use App\Http\Requests\V1\UpdateProbeRequest;
use App\Http\Resources\V1\ProbeCollection;
use App\Http\Resources\V1\ProbeMetricCollection;
use App\Http\Resources\V1\ProbeResource;
use App\Http\Resources\V1\ProbeRuleCollection;
use App\Models\Probe;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $size = (int) $request->input('size', 5);
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
     * Display a listing of the probe metrics.
     *
     * @param Probe $probe
     * @param Request $request
     * @return JsonResponse
     */
    public function metrics(Probe $probe, Request $request): JsonResponse
    {
        $size = (int) $request->input('size', 5);
        $metric_type_id = $request->input('metric_type_id', null);

        if ($metric_type_id) {
            $metrics = $probe->metrics()->where('metric_type_id', $metric_type_id)->orderBy('id', 'desc')->paginate($size);
        } else {
            $metrics = $probe->metrics()->orderBy('id', 'desc')->paginate($size);
        }

        return $this->successResponse(new ProbeMetricCollection($metrics));
    }

    /**
     * Display a listing of the probe rules.
     *
     * @param Probe $probe
     * @param Request $request
     * @return JsonResponse
     */
    public function rules(Probe $probe, Request $request): JsonResponse
    {
        $size = (int) $request->input('size', 5);
        $rules = $probe->rules()->orderBy('id', 'desc')->paginate($size);

        return $this->successResponse(new ProbeRuleCollection($rules));
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
