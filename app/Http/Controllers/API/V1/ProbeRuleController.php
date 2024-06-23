<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Actions\Rules\CreateRuleAction;
use App\Actions\Rules\DeleteRuleAction;
use App\Actions\Rules\UpdateRuleAction;
use App\Http\Requests\V1\StoreProbeRuleRequest;
use App\Http\Requests\V1\UpdateProbeRuleRequest;
use App\Http\Resources\V1\ProbeRuleResource;
use App\Models\ProbeRule;
use App\Traits\JsonResponses;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProbeRuleController
{
    use JsonResponses;
    public function store(StoreProbeRuleRequest $request, CreateRuleAction $createRulesAction): JsonResponse
    {
        $probeRule = $createRulesAction->handle(
            input: $request->only(['metric_type_id','probe_id','condition','operator']),
        );

        return $this->successResponse(new ProbeRuleResource($probeRule), Response::HTTP_CREATED);
    }

    public function update(UpdateProbeRuleRequest $request, ProbeRule $probeRule, UpdateRuleAction $updateRulesAction): JsonResponse
    {
        $probeRule = $updateRulesAction->handle(
            input: $request->only(['operator','probe_id','condition','metric_type_id']),
            probeRule: $probeRule,
        );

        return $this->successResponse(new ProbeRuleResource($probeRule));
    }

    public function destroy(ProbeRule $probeRule, DeleteRuleAction $deleteRulesAction): JsonResponse
    {
        $deleteRulesAction->handle(
            probeRule: $probeRule,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

}
