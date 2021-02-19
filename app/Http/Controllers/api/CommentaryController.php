<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentaryController extends Controller
{
    public function store(Post $post)
    {
        $data = request()->validate([
            'content' => 'required | min: 5 | max: 600'
        ]);

        $userId = request()->user()->id;

        $comment = $post->comments()->create(['user_id' => $userId, 'content' => $data['content']]);

        return response()->json($comment, 201);
    }
}
