<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\DeleteProbeRequest;
use App\Http\Requests\ShowProbeRequest;
use App\Http\Requests\StoreProbeRequest;
use App\Http\Requests\UpdateProbeRequest;
use App\Http\Resources\ProbeResource;
use App\Models\Probes;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbesController extends BaseController
{
    public function index(): JsonResponse
    {
        $probes = Probes::sameTeam()->get();

        return $this->sendResponse(
            result: ProbeResource::collection($probes),
            message: 'Probes retrieved successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function store(StoreProbeRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['team_id'] = $request->user()->currentTeam->id;
        $probe = Probes::create($input);

        return $this->sendResponse(
            result: new ProbeResource($probe),
            message: 'created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function show(ShowProbeRequest $request, Probes $probe): JsonResponse
    {
        return $this->sendResponse(
            result: new ProbeResource($probe),
            message: 'Probe retrieved successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function update(UpdateProbeRequest $request, Probes $probe): JsonResponse
    {
        $probe->update($request->validated());

        return $this->sendResponse(
            result:  new ProbeResource($probe),
            message: 'Probe updated successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function destroy(DeleteProbeRequest $request, Probes $probe): JsonResponse
    {
        $probe->delete();

        return $this->sendResponse(
            result: [],
            message: 'Probe deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }
}
