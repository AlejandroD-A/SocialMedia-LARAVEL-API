<?php

namespace Database\Seeders;

use Illuminate\Cache\TagSet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TagSeeder::class,
            RolesSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            ShortSeeder::class,
            PostTagSeeder::class
        ]);
    }
}
