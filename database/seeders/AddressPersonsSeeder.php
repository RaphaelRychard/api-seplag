<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\AddressPersons;
use Illuminate\Database\Seeder;

class AddressPersonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AddressPersons::factory(10)->create();
    }
}
