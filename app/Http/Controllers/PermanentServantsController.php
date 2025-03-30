<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePermanentServantRequest;
use App\Http\Requests\UpdatePermanentServantsRequest;
use App\Http\Resources\PermanentServantDetailResource;
use App\Http\Resources\PermanentServantResource;
use App\Http\Resources\UpdatePermanentServantResource;
use App\Http\Services\PermanentServantServices;
use App\Models\PermanentServants;
use Illuminate\Http\Request;

class PermanentServantsController extends Controller
{
    protected $permanentServantServices;

    public function __construct(PermanentServantServices $permanentServantServices)
    {
        $this->permanentServantServices = $permanentServantServices;
    }

    public function index(Request $request)
    {
        ds()->clear();
        ds()->queriesOn();

        $query = PermanentServants::query();

        $query->when(
            $request->has('unid_id'),
            fn ($query) => $query->whereHas('person.assignment.unit', function ($q) use ($request) {
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
            'data'       => PermanentServantResource::collection($paginatedResults),
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

    public function show(PermanentServants $permanentServants)
    {
        ds()->clear();
        ds()->queriesOn();
        ds($permanentServants);

        return PermanentServantDetailResource::make($permanentServants);
    }

    public function store(StorePermanentServantRequest $request)
    {
        $data   = $request->validated();
        $result = $this->permanentServantServices->create($data);

        return new PermanentServantResource($result);
    }

    public function update(UpdatePermanentServantsRequest $request, PermanentServants $permanentServants)
    {
        $data = $request->validated();

        $result = $this->permanentServantServices->update($permanentServants, $data);

        return new UpdatePermanentServantResource($result);
    }
}
