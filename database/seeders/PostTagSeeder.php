<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taggables')->truncate();

        $posts = Post::all();
        foreach ($posts as $post) {
            for ($i = 0; $i <= 10; $i++) {

                $post->tags()->toggle(rand(1, 20));
            }
        }
    }
}
