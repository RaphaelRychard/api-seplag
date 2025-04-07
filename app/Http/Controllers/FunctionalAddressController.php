<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchFunctionalAddressRequest;
use App\Http\Resources\FunctionalAddressResource;
use App\Models\Person;

class FunctionalAddressController extends Controller
{
    public function search(SearchFunctionalAddressRequest $request)
    {
        $nameQuery = $request->validated('nome');
        $perPage   = $request->integer('per_page', 10); // valor padrão

        $paginatedResults = $this->getMatchingServants($nameQuery, $perPage);

        return response()->json([
            'data'       => FunctionalAddressResource::collection($paginatedResults),
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

    private function getMatchingServants(string $nameQuery, int $perPage)
    {
        $relationshipPath = 'assignment.unit.addressUnit.address.city';

        return Person::where('nome', 'ILIKE', "%{$nameQuery}%")
            ->whereHas('permanentServant')
            ->with([$relationshipPath])
            ->paginate($perPage);
    }
}
