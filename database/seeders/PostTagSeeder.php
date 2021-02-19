<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Short;
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
        $shorts = Short::all();

        $all = $posts->concat($shorts);

        foreach ($all as $activity) {
            for ($i = 0; $i <= 10; $i++) {

                $activity->tags()->toggle(rand(1, 20));
            }
        }
    }
}
