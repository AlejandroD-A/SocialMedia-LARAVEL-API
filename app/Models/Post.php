<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'cover'];
    protected $dates = ['deleted_at'];
    protected $hidden = ['content', 'user_id'];

    protected $appends = ['commentsCount', 'favouritesCount'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites()->count();
    }

    public function comments()
    {
        return $this->morphMany(Commentary::class, 'commentable');
    }

    public function favourites()
    {
        return $this->morphToMany(User::class, 'favouritable')->withTimestamps();
    }
}
