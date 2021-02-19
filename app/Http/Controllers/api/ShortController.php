<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Intervention\Image\Facades\Image;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\ShortPostRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ShortController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shorts = Short::with([
            'tags:id,name', 'user:id,username,avatar'
        ])
            ->select('shorts.id', 'shorts.content', 'shorts.created_at', 'shorts.user_id')
            ->orderBy('created_at', 'desc')
            ->withCount('favourites')
            ->withCount('comments')

            ->paginate(5);
        return $this->successResponse($shorts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Short $short)
    {
        $short->load('tags', 'user');
        return $this->successResponse($short);
    }

    public function tag(Tag $tag)
    {
        $shorts = $tag->shorts()->orderBy('created_at', 'desc')->paginate(5);
        $shorts->load('tags');
        return $this->successResponse(["tag" => $tag->name, "shorts" => $shorts]);
    }

    public function store(ShortPostRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $tags = [];

        if (array_key_exists('tags', $validated)) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }

        $short = new Short($validated);

        DB::transaction(function () use ($user, $short, $tags) {
            $short->user_id = $user->id;
            $short->save();

            if (count($tags) > 0) {
                $listOfTags = [];
                foreach ($tags as $tag) {
                    $tag = Tag::firstOrCreate($tag);
                    array_push($listOfTags, $tag->id);
                }
                $short->tags()->attach($listOfTags);
            }
        });

        return $this->successResponse($short, 201, 'Short created Succesfully');
    }



    public function getComments(Short $short)
    {
        $comments = $short->comments;
        $comments->load('user');

        return response()->json($comments, 200);
    }
}
