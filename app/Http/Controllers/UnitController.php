<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $paginatedResults = Unit::paginate(10);

        return response()->json([
            'data'       => UnitResource::collection($paginatedResults),
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

    public function show(Unit $unit)
    {
        return UnitResource::make($unit);
    }

    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();

        $unit = Unit::create($data);

        return UnitResource::make($unit);
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $data = $request->validated();

        $unit->update($data);

        return UnitResource::make($unit);
    }
}
