<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
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
}
