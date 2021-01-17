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
        $tags = Tag::all();
        return $this->successResponse($tags);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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
}
