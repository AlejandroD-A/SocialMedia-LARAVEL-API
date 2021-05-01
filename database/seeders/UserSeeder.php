<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(
            [
                'role_id' => '1',
                'name' => 'Alejandro',
                'username' => 'UsuarioUno',
                'email' => 'alejandro@gmail.com',
                'password' => bcrypt('12345678')
            ]
        );
        $user->profile()->create();
    }
}
