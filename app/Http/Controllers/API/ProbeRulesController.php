<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\DeleteProbeRuleRequest;
use App\Http\Requests\StoreProbeRuleRequest;
use App\Http\Requests\UpdateProbeRuleRequest;
use App\Http\Resources\ProbeRuleResource;
use App\Models\ProbeRules;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeRulesController extends BaseController
{
    public function store(StoreProbeRuleRequest $request): JsonResponse
    {
        $input = $request->validated();
        $probeRule = ProbeRules::create($input);

        return $this->sendResponse(
            result: new ProbeRuleResource($probeRule),
            message: 'Rule created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdateProbeRuleRequest $request, ProbeRules $probeRules): JsonResponse
    {
        $probeRules->update($request->validated());

        return $this->sendResponse(
            result: new ProbeRuleResource($probeRules),
            message: 'Rule updated successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function destroy(DeleteProbeRuleRequest $request, ProbeRules $probeRules): JsonResponse
    {
        $probeRules->delete();

        return $this->sendResponse(
            result: [],
            message: 'Rule deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }

}
