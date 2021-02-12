<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\api\ApiResponseController;

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
}
