<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\AddressUnit;
use Illuminate\Database\Seeder;

class AddressUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AddressUnit::factory(10)->create();
    }
}
