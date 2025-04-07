<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTemporaryServantRequest;
use App\Http\Requests\UpdateTemporaryServantRequest;
use App\Http\Resources\DetailsTemporaryServantResource;
use App\Http\Resources\FetchTemporaryServantResource;
use App\Http\Resources\StoreTemporaryServantResource;
use App\Http\Resources\UpdateTemporaryServantResource;
use App\Http\Services\TemporaryServantServices;
use App\Models\TemporaryServants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemporaryServantsController extends Controller
{
    public function __construct(protected TemporaryServantServices $temporaryServantServices)
    {
    }

    public function index(Request $request): JsonResponse
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
            'data'       => FetchTemporaryServantResource::collection($paginatedResults),
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

    public function show(TemporaryServants $temporaryServant): DetailsTemporaryServantResource
    {
        return DetailsTemporaryServantResource::make($temporaryServant);
    }

    public function store(StoreTemporaryServantRequest $request): StoreTemporaryServantResource
    {
        $data   = $request->validated();
        $result = $this->temporaryServantServices->create($data);

        return new StoreTemporaryServantResource($result);
    }

    public function update(UpdateTemporaryServantRequest $request, TemporaryServants $temporaryServant): UpdateTemporaryServantResource
    {
        $data = $request->validated();

        $result = $this->temporaryServantServices->update($temporaryServant, $data);

        return new UpdateTemporaryServantResource($result);
    }
}
