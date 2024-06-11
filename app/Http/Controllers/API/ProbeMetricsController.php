<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Events\CalculatProcessed;
use App\Http\Resources\ProbeMetricResource;
use App\Models\ProbeMetrics;
use App\Models\Probes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProbeMetricsController extends BaseController
{
    public function index(int $id): JsonResponse
    {
        $probe = Probes::where('team_id', auth()->user()->currentTeam->id)->find($id);
        if (is_null($probe)) {
            return $this->sendError('Probe not found.');
        }

        $metrics = $probe->metrics;

        return $this->sendResponse(ProbeMetricResource::collection($metrics), 'Metrics retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'probe_id' => ['required', 'exists:probes,id'],
            'metric_type_id' => ['required', 'exists:metric_types,id'],
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probe = Probes::where('team_id', auth()->user()->currentTeam->id)->find($input['probe_id']);
        if (is_null($probe)) {
            return $this->sendError('Probe not found.');
        }

        $probeMetric = ProbeMetrics::create($input);
        CalculatProcessed::dispatch($probeMetric);

        return $this->sendResponse(new ProbeMetricResource($probeMetric), 'Metric created successfully.', 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $metric = ProbeMetrics::find($id);
        $probe = Probes::where('team_id', auth()->user()->currentTeam->id)->find($metric->probe_id);

        if (is_null($probe)) {
            return $this->sendError('Probe not found.');
        }

        if (is_null($metric)) {
            return $this->sendError('Metric not found.');
        }

        $metric->delete();

        return $this->sendResponse([], 'Metric deleted successfully.', 204);
    }
}
