<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $hidden = ['pivot'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable')->with([
            'tags:id,name', 'user:id,username,avatar'
        ])
            ->latest()
            ->withTimeStamps();
    }

    public function shorts()
    {
        return $this->morphedByMany(Short::class, 'taggable')->withTimestamps();
    }

    public function shortsAndPosts()
    {
        return $this->hasMany(Taggable::class)->with('taggable')->paginate(5);
    }
}
