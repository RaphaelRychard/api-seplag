<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePermanentServantRequest;
use App\Http\Requests\UpdatePermanentServantsRequest;
use App\Http\Resources\DetailsPermanentServantResource;
use App\Http\Resources\FetchPermanentServantResource;
use App\Http\Resources\StorePermanentServantResource;
use App\Http\Resources\UpdatePermanentServantResource;
use App\Http\Services\PermanentServantServices;
use App\Models\PermanentServants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermanentServantsController extends Controller
{
    public function __construct(protected PermanentServantServices $permanentServantServices)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = PermanentServants::query();

        $query->when(
            $request->has('unid_id'),
            fn ($query) => $query->whereHas('person.assignment.unit', function ($q) use ($request): void {
                $q->where('id', $request->query('unid_id'));
            })
        );

        $query->when(
            $request->has('nome'),
            fn ($query) => $query->whereHas('person', function ($q) use ($request): void {
                $q->where('nome', 'like', '%' . $request->query('nome') . '%');
            })
        );

        $query->when(
            $request->has('with'),
            fn ($query) => $query->with(explode(',', $request->query('with')))
        );

        $perPage          = $request->query('per_page', 10);
        $paginatedResults = $query->paginate($perPage);

        return response()->json([
            'data'       => FetchPermanentServantResource::collection($paginatedResults),
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

    public function show(PermanentServants $permanentServant): DetailsPermanentServantResource
    {
        return DetailsPermanentServantResource::make($permanentServant);
    }

    public function store(StorePermanentServantRequest $request): StorePermanentServantResource
    {
        $data   = $request->validated();
        $result = $this->permanentServantServices->create($data);

        return new StorePermanentServantResource($result);
    }

    public function update(UpdatePermanentServantsRequest $request, PermanentServants $permanentServant): UpdatePermanentServantResource
    {
        $data = $request->validated();

        $result = $this->permanentServantServices->update($permanentServant, $data);

        return new UpdatePermanentServantResource($result);
    }
}
