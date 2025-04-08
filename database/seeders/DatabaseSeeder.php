<?php

declare(strict_types = 1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AddressPersonsSeeder::class,
            AddressUnitSeeder::class,
            AddressSeeder::class,
            AssignmentSeeder::class,
            CitySeeder::class,
            PermanentServantsSeeder::class,
            PersonSeeder::class,
            PersonsPhotoSeeder::class,
            TemporaryServantSeeder::class,
            UnitSeeder::class,
            UserSeeder::class,
        ]);
    }
}
