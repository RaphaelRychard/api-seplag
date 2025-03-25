<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Mappers\PersonMapper;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        return PersonResource::collection(
            Person::all(),
        );
    }

    public function show(Person $person)
    {
        return PersonResource::make($person);
    }

    public function store(StorePersonRequest $request)
    {
        $data   = $request->validated();
        $mapper = PersonMapper::mapper($data);

        $person = Person::create($mapper);

        return PersonResource::make($person);
    }

    public function update(UpdatePersonRequest $request, Person $person)
    {
        $data   = $request->validated();
        $mapper = PersonMapper::mapper($data);

        $person->update($mapper);

        return PersonResource::make($person);
    }
}
