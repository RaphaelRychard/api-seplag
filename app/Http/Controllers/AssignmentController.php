<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index()
    {
        return AssignmentResource::collection(
            Assignment::all(),
        );
    }

    public function show(Assignment $assignment)
    {
        return AssignmentResource::make($assignment);
    }

    public function store(StoreAssignmentRequest $request)
    {
        $data = $request->validated();

        $assignment = Assignment::create($data);

        return AssignmentResource::make($assignment);
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $data = $request->validated();

        $assignment->update($data);

        return AssignmentResource::make($assignment);
    }
}
