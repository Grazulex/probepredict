<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Resources\ProbeResource;
use App\Models\Probes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProbesController extends BaseController
{
    public function index(): JsonResponse
    {
        $probes = Probes::where('team_id', auth()->user()->currentTeam->id)->get();

        return $this->sendResponse(ProbeResource::collection($probes), 'Probes retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probes'],
            'description' => 'required',
            'probe_type_id' => 'required|exists:probe_types,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $input['team_id'] = $request->user()->currentTeam->id;

        $probe = Probes::create($input);

        return $this->sendResponse(new ProbeResource($probe), 'Probe created successfully.', 201);
    }

    public function show(int $id): JsonResponse
    {
        $probes = Probes::where('team_id', auth()->user()->currentTeam->id)->find($id);

        if (is_null($probes)) {
            return $this->sendError('Probe not found.');
        }

        return $this->sendResponse(new ProbeResource($probes), 'Probe retrieved successfully.');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $probes = Probes::where('team_id', auth()->user()->currentTeam->id)->find($id);

        if (is_null($probes)) {
            return $this->sendError('Probe not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probes'],
            'description' => 'required',
            'probe_type_id' => 'required|exists:probe_types,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $probes->name = $input['name'];
        $probes->description = $input['description'];
        $probes->probe_type_id = $input['probe_type_id'];
        $probes->save();

        return $this->sendResponse(new ProbeResource($probes), 'Probe updated successfully.');
    }

    public function destroy(int $id): JsonResponse
    {
        $probes = Probes::where('team_id', auth()->user()->currentTeam->id)->find($id);
        $probes->delete();

        return $this->sendResponse([], 'Probe deleted successfully.');
    }
}
