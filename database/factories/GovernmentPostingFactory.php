<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Person;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GovernmentPosting>
 */
class GovernmentPostingFactory extends Factory
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
            'unid_id'          => Unit::factory(),
            'lot_data_lotacao' => fake()->dateTimeBetween('-10 years', 'now'),
            'lot_data_remocao' => fake()->dateTimeBetween('-1 years', 'now'),
            'lot_portaria'     => fake()->numerify('Portaria ####'),
        ];
    }
}
