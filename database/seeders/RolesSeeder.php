<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::create(['key' => 'admin', 'name' => 'Administrador ', 'description' => 'Administrador']);
        Role::create(['key' => 'regular', 'name' => 'Regular User', 'description' => 'Regular']);
    }
}
