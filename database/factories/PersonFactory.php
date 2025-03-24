<?php

declare(strict_types = 1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sex = [
            'masculino',
            'feminino',
            'outros',
        ];

        return [
            "pes_nome"            => fake()->name(),
            "pes_data_nascimento" => fake()->date(),
            "pes_sexo"            => fake()->randomElement($sex),
            "per_mae"             => fake()->name(),
            "per_pai"             => fake()->name(),
        ];
    }
}
