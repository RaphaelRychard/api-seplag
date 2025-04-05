<?php

declare(strict_types = 1);

use App\Models\Assignment;
use Illuminate\Contracts\Auth\Authenticatable;

use function Pest\Laravel\getJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to fetch a paginated list of assignments with correct structure and pagination', function () {
    Assignment::factory()->count(15)->create();

    $sut = getJson(route('api.assignment.index'));

    $sut->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'pes_id',
                    'unid_id',
                    'data_lotacao',
                    'data_remocao',
                    'portaria',
                ],
            ],
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to',
            ],
        ])
        ->assertJsonFragment([
            'current_page' => 1,
            'per_page'     => 10,
        ]);
});

it('should be able to limit results to 10 per page by default', function () {
    Assignment::factory()->count(25)->create();

    $sut = getJson(route('api.assignment.index'));

    $sut->assertOk();
    expect(count($sut->json('data')))->toBe(10);
});

it('should be able to return an empty list when no assignments exist', function () {
    $sut = getJson(route('api.assignment.index'));

    $sut->assertOk()
        ->assertJson([
            'data'       => [],
            'pagination' => [
                'total'        => 0,
                'per_page'     => 10,
                'current_page' => 1,
                'last_page'    => 1,
                'from'         => null,
                'to'           => null,
            ],
        ]);
});

it('should be able to return 10 items on page 1 and 5 items on page 2', function () {
    Assignment::factory()->count(15)->create();

    $page1 = getJson(route('api.assignment.index', ['page' => 1]));
    $page1->assertOk();
    expect(count($page1->json('data')))->toBe(10);

    $page2 = getJson(route('api.assignment.index', ['page' => 2]));
    $page2->assertOk();
    expect(count($page2->json('data')))->toBe(5);
});
