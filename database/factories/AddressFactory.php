<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $addressType = [
            'residencial'     => 'Endereço Residencial',
            'comercial'       => 'Endereço Comercial',
            'correspondencia' => 'Endereço de Correspondência',
            'entrega'         => 'Endereço de Entrega',
            'fiscal'          => 'Endereço Fiscal',
            'faturamento'     => 'Endereço de Faturamento',
        ];

        return [
            'end_tipo_logradouro' => fake()->randomElement($addressType),
            'end_logradouro'      => fake()->streetAddress(),
            'end_numero'          => fake()->buildingNumber(),
            'end_bairro'          => fake()->cityPrefix(),
            'id'                  => City::factory(),
        ];
    }
}
