<?php

namespace Database\Seeders;

use App\Models\Post;
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
        Post::truncate();


        for ($i = 0; $i <= 20; $i++) {
            Post::create([
                'title' => "Nuevo Post $i",
                'content' => "Content Post $i",
                'cover' => 'https://previews.123rf.com/images/nicolasprimola/nicolasprimola1512/nicolasprimola151200129/50519008-ilustraci%C3%B3n-digital-de-un-iliensis-ochotona-ili-pika.jpg'
            ]);
        }
    }
}
