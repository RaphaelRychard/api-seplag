<?php

declare(strict_types = 1);

use App\Models\Assignment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\patchJson;

beforeEach(fn (): Authenticatable => login());

it('should be able to remove (end) an assignment', function (): void {
    $assignment = Assignment::factory()->create([
        'data_remocao' => null,
    ]);

    $data = [
        'data_remocao' => Carbon::now()->toDateString(),
    ];

    $sut = patchJson(route('api.assignment.remove', $assignment), $data);

    $sut->assertOk()
        ->assertJsonPath('mensagem', 'Remoção feita com sucesso.');

    assertDatabaseHas('lotacao', [
        'id'           => $assignment->id,
        'data_remocao' => $data['data_remocao'],
    ]);
});

it('should not remove an already removed assignment', function (): void {
    $assignment = Assignment::factory()->create([
        'data_remocao' => now()->subDay()->toDateString(),
    ]);

    $sut = patchJson(route('api.assignment.remove', $assignment), [
        'data_remocao' => now()->toDateString(),
    ]);

    $sut->assertUnprocessable();
});

it('should not remove with invalid date', function (): void {
    $assignment = Assignment::factory()->create([
        'data_remocao' => null,
    ]);

    $sut = patchJson(route('api.assignment.remove', $assignment), [
        'data_remocao' => 'not-a-date',
    ]);

    $sut->assertUnprocessable()
        ->assertJsonValidationErrors(['data_remocao']);
});
