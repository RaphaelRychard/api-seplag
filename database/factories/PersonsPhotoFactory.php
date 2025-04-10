<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonsPhoto>
 */
class PersonsPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pes_id' => Person::factory(),
            'data'   => now()->toDateString(),
            'bucket' => 'servidores',
            'hash'   => 'foto_' . fake()->unique()->uuid() . '.jpg',
        ];
    }
}
