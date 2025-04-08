<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\PermanentServants;
use Illuminate\Database\Seeder;

class PermanentServantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PermanentServants::factory(10)->create();
    }
}
