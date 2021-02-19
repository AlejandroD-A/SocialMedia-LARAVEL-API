<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Short;
use Illuminate\Database\Seeder;

class ShortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Short::truncate();

        $user = User::first();
        for ($i = 0; $i <= 20; $i++) {
            $user->shorts()->create([
                'content' => "Content Short $i",
            ]);
        }
    }
}
