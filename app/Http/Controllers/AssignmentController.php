<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\RemoveAssignmentRequest;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;

class AssignmentController extends Controller
{
    public function index(): JsonResponse
    {
        $paginatedResults = Assignment::paginate(10);

        return response()->json([
            'data'       => AssignmentResource::collection($paginatedResults),
            'pagination' => [
                'total'        => $paginatedResults->total(),
                'per_page'     => $paginatedResults->perPage(),
                'current_page' => $paginatedResults->currentPage(),
                'last_page'    => $paginatedResults->lastPage(),
                'from'         => $paginatedResults->firstItem(),
                'to'           => $paginatedResults->lastItem(),
            ],
        ]);
    }

    public function show(Assignment $assignment): AssignmentResource
    {
        return AssignmentResource::make($assignment);
    }

    public function store(StoreAssignmentRequest $request): AssignmentResource
    {
        $data = $request->validated();

        $assignment = Assignment::create($data);

        return AssignmentResource::make($assignment);
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment): AssignmentResource
    {
        $data = $request->validated();

        $assignment->update($data);

        return AssignmentResource::make($assignment);
    }

    public function remove(RemoveAssignmentRequest $request, Assignment $assignment): JsonResponse
    {
        $data = $request->validated();

        $assignment->update($data);

        return response()->json([
            'data' => [
                'mensagem' => 'Remoção feita com sucesso.',
            ],
        ]);
    }
}
