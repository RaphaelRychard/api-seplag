<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermanentServantRequest;
use App\Http\Resources\PermanentServantResource;
use App\Http\Services\CreatePermanentServantService;

class PermanentServantsController extends Controller
{
    protected $createPermanentServantService;

    public function __construct(CreatePermanentServantService $createPermanentServantService)
    {
        $this->createPermanentServantService = $createPermanentServantService;
    }

    public function store(CreatePermanentServantRequest $request)
    {
        $data   = $request->validated();
        $result = $this->createPermanentServantService->create($data);

        return new PermanentServantResource($result);
    }
}
