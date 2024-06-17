<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Resources\Collections\ProbeTypesCollection;
use App\Http\Resources\ProbeTypesResource;
use App\Models\ProbeTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

final class ProbeTypesController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->sendResponse(
            result: new ProbeTypesCollection(ProbeTypes::all()),
            message: 'Probe Types retrieved successfully.',
        );
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probe_types'],
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probe_types = ProbeTypes::create($input);

        return $this->sendResponse(new ProbeTypesResource($probe_types), 'Probe Type created successfully.', 201);
    }

    public function show(int $id): JsonResponse
    {
        $probe_types = ProbeTypes::find($id);

        if (null === $probe_types) {
            return $this->sendError('Probe not found.');
        }

        return $this->sendResponse(new ProbeTypesResource($probe_types), 'Probe Type retrieved successfully.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $probe_types = ProbeTypes::find($id);

        if (null === $probe_types) {
            return $this->sendError('Probe not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probe_types'],
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probe_types->name = $input['name'];
        $probe_types->description = $input['description'];
        $probe_types->save();

        return $this->sendResponse(new ProbeTypesResource($probe_types), 'Probe Type updated successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $probe_types = ProbeTypes::find($id);

        if (null === $probe_types) {
            return $this->sendError('Probe not found.');
        }

        $probe_types->delete();

        return $this->sendResponse(result: [], message: 'Product deleted successfully.', status: 204);
    }
}
