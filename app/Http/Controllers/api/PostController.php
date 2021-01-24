<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostPost;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\api\ApiResponseController;
use Illuminate\Support\Facades\DB;

class PostController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(20);
        $posts->load('tags');

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
        $validatedPost = ['title' => $validated['title'], 'content' => $validated['content'], 'cover' => $validated['cover']];

        $post = new Post($validatedPost);

        DB::transaction(function () use ($user, $post, $validated) {

            $tags = $validated['tags'];
            $listOfTags = [];

            foreach ($tags as $tag) {
                $tag = Tag::firstOrCreate($tag);
                array_push($listOfTags, $tag->id);
            }

            $post->user_id = $user->id;

            $post->save();

            $post->tags()->attach($listOfTags);
        });

        return $this->successResponse($post, 201, 'Post created Succesfully');
    }
}
