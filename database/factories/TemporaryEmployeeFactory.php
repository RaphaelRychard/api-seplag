<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemporaryEmployee>
 */
class TemporaryEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'            => Person::factory(),
            'data_admissao' => fake()->dateTimeBetween('-10 years', 'now'),
            'data_demissao' => fake()->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
