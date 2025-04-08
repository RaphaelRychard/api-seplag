<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\PersonsPhoto;
use Illuminate\Database\Seeder;

class PersonsPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersonsPhoto::factory(10)->create();
    }
}
