<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddressUnit>
 */
class AddressUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unid_id' => Unit::factory(),
            'end_id'  => Address::factory(),
        ];
    }
}
