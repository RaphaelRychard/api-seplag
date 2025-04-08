<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\TemporaryServants;
use Illuminate\Database\Seeder;

class TemporaryServantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TemporaryServants::factory(10)->create();
    }
}
