<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;

class UnitController extends Controller
{
    public function store(StoreUnitRequest $request)
    {
        $data = $request->validated();
        $unit = Unit::create($data);

        return UnitResource::make($unit);
    }
}
