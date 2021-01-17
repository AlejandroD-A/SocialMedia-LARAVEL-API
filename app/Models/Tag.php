<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable')->withTimestamps();
    }

    public function taggables()
    {
        return Taggable::where('tag_id', $this->id)->get();
    }
}
