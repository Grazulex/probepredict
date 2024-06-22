<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\V1\ProbeTypeResource;
use App\Models\ProbeType;
use App\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProbeTypeController
{
    use JsonResponses;

    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->query('size', 5);
        $probe_types = ProbeType::all()->paginate($size);

        return $this->successResponse(ProbeTypeResource::collection($probe_types));
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probe_types'],
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $probe_types = ProbeType::create($input);

        return $this->successResponse(new ProbeTypeResource($probe_types), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $probe_types = ProbeType::find($id);

        if (null === $probe_types) {
            return $this->errorResponse('Probe not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(new ProbeTypeResource($probe_types));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $probe_types = ProbeType::find($id);

        if (null === $probe_types) {
            return $this->errorResponse('Probe not found.', Response::HTTP_NOT_FOUND);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'unique:probe_types'],
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $probe_types->name = $input['name'];
        $probe_types->description = $input['description'];
        $probe_types->save();

        return $this->successResponse(new ProbeTypeResource($probe_types));
    }

    public function destroy(int $id): JsonResponse
    {
        $probe_types = ProbeType::find($id);

        if (null === $probe_types) {
            return $this->errorResponse('Probe not found.', Response::HTTP_NOT_FOUND);
        }

        $probe_types->delete();

        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }
}
