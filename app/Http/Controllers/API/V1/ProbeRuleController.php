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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProbeRuleController
 *
 * @package App\Http\Controllers\API\V1
 *
 * This controller handles all the HTTP requests related to ProbeRules.
 */
final class ProbeRuleController
{
    use JsonResponses;

    /**
     * Store a newly created probe rule in storage.
     *
     * @param StoreProbeRuleRequest $request
     * @param CreateRuleAction $createRulesAction
     * @return JsonResponse
     */
    public function store(StoreProbeRuleRequest $request, CreateRuleAction $createRulesAction): JsonResponse
    {
        $probeRule = $createRulesAction->handle(
            input: $this->getRuleAttributes($request),
        );

        return $this->successResponse(new ProbeRuleResource($probeRule), Response::HTTP_CREATED);
    }

    /**
     * Update the specified probe rule in storage.
     *
     * @param UpdateProbeRuleRequest $request
     * @param ProbeRule $probeRule
     * @param UpdateRuleAction $updateRulesAction
     * @return JsonResponse
     */
    public function update(UpdateProbeRuleRequest $request, ProbeRule $probeRule, UpdateRuleAction $updateRulesAction): JsonResponse
    {
        $probeRule = $updateRulesAction->handle(
            input: $this->getRuleAttributes($request),
            probeRule: $probeRule,
        );

        return $this->successResponse(new ProbeRuleResource($probeRule));
    }

    /**
     * Remove the specified probe rule from storage.
     *
     * @param ProbeRule $probeRule
     * @param DeleteRuleAction $deleteRulesAction
     * @return JsonResponse
     */
    public function destroy(ProbeRule $probeRule, DeleteRuleAction $deleteRulesAction): JsonResponse
    {
        $deleteRulesAction->handle(
            probeRule: $probeRule,
        );

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param  Request  $request
     * @return array
     */
    private function getRuleAttributes(Request $request): array
    {
        return $request->only(['operator','probe_id','condition','metric_type_id']);
    }

}
