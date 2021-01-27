<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostPost;
use Illuminate\Support\Facades\Auth;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\api\ApiResponseController;

class PostController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['tags:id,name', 'user:id,username,avatar'])->orderBy('created_at', 'desc')->paginate(5);



        return $this->successResponse($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load('tags');
        return $this->successResponse($post);
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()->orderBy('created_at', 'desc')->paginate(5);
        $posts->load('tags');
        return $this->successResponse(["tag" => $tag->name, "posts" => $posts]);
    }

    public function store(StorePostPost $request)
    {
        $user = $request->user();
        $validated = $request->validated();


        $tags = [];
        if (array_key_exists('tags', $validated)) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }

        $post = new Post($validated);

        $imagePath = $validated['cover']->store('uploads', 'public');
        dd($imagePath);
        $image = Image::make("storage/{$imagePath}");
        $image->save();

        $post->cover = $imagePath;

        DB::transaction(function () use ($user, $post, $tags) {
            $post->user_id = $user->id;

            $post->save();
            if (count($tags) > 0) {

                $listOfTags = [];
                foreach ($tags as $tag) {
                    $tag = Tag::firstOrCreate($tag);
                    array_push($listOfTags, $tag->id);
                }

                $post->tags()->attach($listOfTags);
            }
        });

        return $this->successResponse($post, 201, 'Post created Succesfully');
    }
}
