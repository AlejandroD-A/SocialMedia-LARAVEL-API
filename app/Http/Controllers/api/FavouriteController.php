<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use App\Models\Favourite;
use App\Models\Favouritable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FavouriteController extends ApiResponseController
{
    public function index()
    {
        $user = request()->user();


        $favourites = Favouritable::join('posts', function ($join) {
            $join->on('favouritables.favouritable_id', '=', 'posts.id')
                ->where('favouritables.favouritable_type', '=', 'App\\Models\\Post');
        })
            ->select('posts.id', 'posts.title', 'posts.cover', 'favouritables.favouritable_type')
            ->where('favouritables.user_id', '=', $user->id)
            ->orderBy('favouritables.created_at', 'desc')
            ->get();


        return $this->successResponse($favourites);
    }
    public function store(Post $post)
    {
        $user = request()->user();

        $attach = $post->favourites()->toggle($user->id);

        $relation = count($attach['attached']) ? 'Added' : 'removed';

        return $this->successResponse($relation, 201);
    }
}
