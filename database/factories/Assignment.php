<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Person;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
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
            'unid_id'      => Unit::factory(),
            'data_lotacao' => fake()->dateTimeBetween('-10 years', 'now'),
            'data_remocao' => fake()->dateTimeBetween('-1 years', 'now'),
            'portaria'     => fake()->numerify('Portaria ####'),
        ];
    }
}
