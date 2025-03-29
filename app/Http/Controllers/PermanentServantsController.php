<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePermanentServantsRequest;
use App\Http\Requests\UpdatePermanentServantsRequest;
use App\Http\Resources\PermanentServantResource;
use App\Models\PermanentServants;

class PermanentServantsController extends Controller
{
    public function index()
    {
        return PermanentServantResource::collection(
            PermanentServants::all(),
        );
    }

    public function show(PermanentServants $permanentServants)
    {
        return PermanentServantResource::make($permanentServants);
    }

    public function store(StorePermanentServantsRequest $request)
    {
        $data = $request->validated();

        $permanentServants = PermanentServants::create($data);

        return PermanentServantResource::make($permanentServants);
    }

    public function update(UpdatePermanentServantsRequest $request, PermanentServants $permanentServants)
    {
        $data = $request->validated();

        $permanentServants->update($data);

        return PermanentServantResource::make($permanentServants);
    }
}
