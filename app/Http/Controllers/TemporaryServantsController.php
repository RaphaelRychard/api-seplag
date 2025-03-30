<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemporaryServantRequest;
use App\Http\Requests\UpdateTemporaryServantRequest;
use App\Http\Resources\TemporaryServantDetailResource;
use App\Http\Resources\TemporaryServantResource;
use App\Http\Resources\UpdateTemporaryServantResource;
use App\Http\Services\TemporaryServantServices;
use App\Models\TemporaryServants;
use Illuminate\Http\Request;

class TemporaryServantsController extends Controller
{
    public function __construct(protected TemporaryServantServices $temporaryServantServices)
    {
    }

    public function index(Request $request)
    {
        $query = TemporaryServants::query();

        $query->when(
            $request->has('unid_id'),
            fn ($query) => $query->whereHas('person.assignment.unit', function ($q) use ($request): void {
                $q->where('id', $request->query('unid_id'));
            })
        );

        $query->when(
            $request->has('with'),
            fn ($query) => $query->with(explode(',', $request->query('with')))
        );

        $perPage = $request->query('per_page', 10);

        $paginatedResults = $query->paginate($perPage);

        return response()->json([
            'data'       => TemporaryServantResource::collection($paginatedResults),
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

    public function show(TemporaryServants $temporaryServants)
    {
        ds()->clear();
        ds($temporaryServants);

        return TemporaryServantDetailResource::make($temporaryServants);
    }

    public function store(StoreTemporaryServantRequest $request): TemporaryServantResource
    {
        $data   = $request->validated();
        $result = $this->temporaryServantServices->create($data);

        return new TemporaryServantResource($result);
    }

    public function update(UpdateTemporaryServantRequest $request, TemporaryServants $temporaryServants): UpdateTemporaryServantResource
    {
        $data = $request->validated();

        $result = $this->temporaryServantServices->update($temporaryServants, $data);

        return new UpdateTemporaryServantResource($result);
    }
}
