<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::first();
        for ($i = 0; $i <= 20; $i++) {
            $user->posts()->create([
                'title' => "Nuevo Post $i",
                'content' => "Content Post $i",
                'cover' => 'https://previews.123rf.com/images/nicolasprimola/nicolasprimola1512/nicolasprimola151200129/50519008-ilustraci%C3%B3n-digital-de-un-iliensis-ochotona-ili-pika.jpg'
            ]);
        }
    }
}
