<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Resources\MetricTypeResource;
use App\Models\MetricTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MetricTypesController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->sendResponse(MetricTypeResource::collection(MetricTypes::all()), 'Metric Types retrieved successfully.');
    }
   public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:metric_types'],
            'description' => 'required',
            'unit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $metric_types = MetricTypes::create($input);

        return $this->sendResponse(new MetricTypeResource($metric_types), 'Metric Type created successfully.', 201);
    }

    public function show(int $id): JsonResponse
    {
        $metric_types = MetricTypes::find($id);

        if (is_null($metric_types)) {
            return $this->sendError('Metric not found.');
        }

        return $this->sendResponse(new MetricTypeResource($metric_types), 'Metric Type retrieved successfully.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $metric_types = MetricTypes::find($id);

        if (is_null($metric_types)) {
            return $this->sendError('Metric not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:mertic_types'],
            'description' => 'required',
            'unit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $metric_types->name = $input['name'];
        $metric_types->description = $input['description'];
        $metric_types->unit = $input['unit'];
        $metric_types->save();

        return $this->sendResponse(new MetricTypeResource($metric_types), 'Metric Type updated successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $metric_types = MetricTypes::find($id);

        if (is_null($metric_types)) {
            return $this->sendError('Metric not found.');
        }

        $metric_types->delete();

        return $this->sendResponse(result: [], message: 'Metric deleted successfully.', status: 204);
    }
}
