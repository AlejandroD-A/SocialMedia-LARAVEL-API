<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Taggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\api\ApiResponseController;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TagController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::paginate(20);
        return $this->successResponse($tags);
    }

    /**
     * Display Thrend in last 3 hours.
     *
     * @return \Illuminate\Http\Response
     */
    public function thrends()
    {
        $tags = DB::table('taggables')
            ->join('tags', 'taggables.tag_id', '=', 'tags.id')
            ->select(DB::raw('count(*) as count'), 'tags.id', 'tags.name')
            ->where('taggables.created_at', '>=', Carbon::now()->subHours(3))
            ->groupBy('tags.id', 'tags.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        return $this->successResponse($tags);
    }

    public function search($param)
    {
        $tag = Tag::select('name')
            ->where('name', 'LIKE', "$param%")
            ->limit(3)
            ->get();

        if ($tag === null) {
            $tag = [];
        }
        return $this->successResponse($tag);
    }


    public function getAll(Tag $tag)
    {
        $all = Taggable::with('taggable.user:id,username,avatar', 'taggable.tags:id,name')
            ->where('tag_id', '=', $tag->id)
            ->latest()
            ->paginate(8);

        return $this->successResponse($all);
    }
}
