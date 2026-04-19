<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\seeder_items\CatalogSeeder;
use Database\Seeders\seeder_items\CustomerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}
