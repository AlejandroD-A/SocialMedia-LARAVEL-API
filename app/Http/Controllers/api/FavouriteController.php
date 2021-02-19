<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use App\Models\Short;
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

        $favourites = $user->favourites()->latest()->get();

        return $this->successResponse($favourites);
    }
    public function storePost(Post $post)
    {
        $user = request()->user();

        $attach = $post->favourites()->toggle($user->id);

        $relation = count($attach['attached']) ? 'Added' : 'removed';

        return $this->successResponse($relation, 201);
    }

    public function storeShort(Short $short)
    {
        $user = request()->user();

        $attach = $short->favourites()->toggle($user->id);

        $relation = count($attach['attached']) ? 'Added' : 'removed';

        return $this->successResponse($relation, 201);
    }
}
