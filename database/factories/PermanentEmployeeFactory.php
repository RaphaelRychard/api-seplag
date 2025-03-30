<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PermanentEmployee>
 */
class PermanentEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'           => Person::factory(),
            'se_matricula' => fake()->randomNumber(8, true),
        ];
    }
}
