<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Short;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\http\Requests\StorePostPost;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\api\ApiResponseController;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with([
            'tags:id,name', 'user:id,username'
        ])
            ->latest()

            ->paginate(5);

        return $this->successResponse($posts);
    }

    public function perspective(Request $request)
    {
        $users = request()->user()->following;

        $posts = Post::whereIn('user_id', $users)
            ->with([
                'tags:id,name', 'user:id,username'
            ])
            ->latest()
            ->paginate(5);

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
        $post->load('tags:name', 'user:id,username,avatar');
        $post = $post->makeVisible('content')->toArray();

        return $this->successResponse($post);
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->shortsAndPosts();

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

        $imagePath = $this->uploadImage($validated['cover']);
        $post = new Post($validated);

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

    public function destroy(Post $post)
    {
        $post->delete();



        return response()->json(['Post deleted ', 200]);
    }

    public function restore($post_id)
    {
        Post::withTrashed()->findOrFail($post_id)->restore();

        return response()->json(['Post restored ', 200]);
    }

    public function getComments(Post $post)
    {
        $comments = $post->comments;
        $comments->load('user');

        return response()->json($comments, 200);
    }
    private function uploadImage($image)
    {

        if (App::environment('local')) {
            $imagePath = $image->store('uploads', 'public');
            $imageUpload = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 400);
            $imageUpload->save();
            return "http://localhost:8000/storage/{$imagePath}";
        } else {

            $imagePath = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'uploads',
                'transformation' => [
                    'width' => 1200,
                    'crop' => 'limit',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ]
            ])->getSecurePath();

            return $imagePath;
        }
    }
}
