<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Actions\Probes\CreateProbesAction;
use App\Actions\Probes\DeleteProbesAction;
use App\Actions\Probes\UpdateProbesAction;
use App\Http\Requests\StoreProbeRequest;
use App\Http\Requests\UpdateProbeRequest;
use App\Http\Resources\Collections\ProbesCollection;
use App\Http\Resources\ProbesResource;
use App\Models\Probes;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProbesController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $size = $request->query('size', 5);
        $probes = Probes::sameTeam()->paginate($size);

        return $this->sendResponse(
            result: new ProbesCollection($probes),
            message: 'Probes retrieved successfully.',
            status: Response::HTTP_OK,
            paginator: true,
        );
    }

    public function store(StoreProbeRequest $request, CreateProbesAction $action): JsonResponse
    {
        $probe = $action->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            user: $request->user(),
        );

        return $this->sendResponse(
            result: new ProbesResource($probe),
            message: 'created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function show(Probes $probe): JsonResponse
    {
        return $this->sendResponse(
            result: new ProbesResource($probe),
            message: 'Probe retrieved successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function update(UpdateProbeRequest $request, Probes $probe, UpdateProbesAction $action): JsonResponse
    {
        $probe = $action->handle(
            input: $request->only(['name', 'description', 'probe_type_id']),
            probes: $probe,
        );

        return $this->sendResponse(
            result:  new ProbesResource($probe),
            message: 'Probe updated successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function destroy(Probes $probe, DeleteProbesAction $action): JsonResponse
    {
        $action->handle(
            probes: $probe,
        );

        return $this->sendResponse(
            result: [],
            message: 'Probe deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
