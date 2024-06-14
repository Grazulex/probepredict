<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Resources\ProbeRuleResource;
use App\Models\ProbeRules;
use App\Models\Probes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class ProbeRulesController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'probe_id' => ['required', 'exists:probes,id'],
            'metric_type_id' => ['required', 'exists:metric_types,id'],
            'operator' => ['required'],
            'condition' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probe = Probes::sameTeam()->find($input['probe_id']);
        if (null === $probe) {
            return $this->sendError('Probe not found.');
        }

        $probeRule = ProbeRules::create($input);

        return $this->sendResponse(new ProbeRuleResource($probeRule), 'Rule created successfully.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $probeRule = ProbeRules::find($id);
        $probe = Probes::sameTeam()->find($probeRule->probe_id);

        if (null === $probe) {
            return $this->sendError('Probe not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'probe_id' => ['required', 'exists:probes,id'],
            'metric_type_id' => ['required', 'exists:metric_types,id'],
            'operator' => ['required'],
            'condition' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probeRule->probe_id = $input['probe_id'];
        $probeRule->metric_type_id = $input['metric_type_id'];
        $probeRule->operator = $input['operator'];
        $probeRule->condition = $input['condition'];
        $probeRule->save();

        return $this->sendResponse(new ProbeRuleResource($probeRule), 'Rule updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $rule = ProbeRules::find($id);
        $probe = Probes::sameTeam()->find($rule->probe_id);

        if (null === $probe) {
            return $this->sendError('Probe not found.');
        }

        if (null === $rule) {
            return $this->sendError('Rule not found.');
        }

        $rule->delete();

        return $this->sendResponse([], 'Rule deleted successfully.', 204);
    }
}
