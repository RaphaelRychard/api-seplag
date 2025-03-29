<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

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
        $data = $request->validated();

        $person = Person::create($data);

        return PersonResource::make($person);
    }

    public function update(UpdatePersonRequest $request, Person $person)
    {
        $data = $request->validated();

        $person->update($data);

        return PersonResource::make($person);
    }
}
