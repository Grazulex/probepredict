<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Actions\Rules\CreateRulesAction;
use App\Actions\Rules\DeleteRulesAction;
use App\Actions\Rules\UpdateRulesAction;
use App\Http\Requests\StoreProbeRuleRequest;
use App\Http\Requests\UpdateProbeRuleRequest;
use App\Http\Resources\ProbeRulesResource;
use App\Models\ProbeRules;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeRulesController extends BaseController
{
    public function store(StoreProbeRuleRequest $request, CreateRulesAction $action): JsonResponse
    {
        $probeRule = $action->handle(
            input: $request->only(['metric_type_id','probe_id','condition','operator']),
        );

        return $this->sendResponse(
            result: new ProbeRulesResource($probeRule),
            message: 'Rule created successfully.',
            status: Response::HTTP_CREATED,
        );
    }

    public function update(UpdateProbeRuleRequest $request, ProbeRules $probeRules, UpdateRulesAction $action): JsonResponse
    {
        $probeRules = $action->handle(
            input: $request->only(['operator','probe_id','condition','metric_type_id']),
            probeRules: $probeRules,
        );

        return $this->sendResponse(
            result: new ProbeRulesResource($probeRules),
            message: 'Rule updated successfully.',
            status: Response::HTTP_OK,
        );
    }

    public function destroy(ProbeRules $probeRules, DeleteRulesAction $action): JsonResponse
    {
        $action->handle(
            probeRules: $probeRules,
        );

        return $this->sendResponse(
            result: [],
            message: 'Rule deleted successfully.',
            status: Response::HTTP_NO_CONTENT,
        );
    }

}
