<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\PermanentServants;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PermanentServants>
 */
class PermanentServantsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pes_id'           => Person::factory(),
            'se_matricula' => fake()->randomNumber(8, true),
        ];
    }
}
