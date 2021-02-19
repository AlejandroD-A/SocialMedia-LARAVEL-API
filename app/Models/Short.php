<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Short extends Model
{
    use HasFactory;
    protected $fillable = ['content'];

    protected $hidden = ['user_id'];

    protected $appends = ['commentsCount', 'favouritesCount'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Commentary::class, 'commentable');
    }

    public function favourites()
    {
        return $this->morphToMany(User::class, 'favouritable')->withTimestamps();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites()->count();
    }
}
