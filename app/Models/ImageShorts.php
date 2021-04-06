<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageShorts extends Model
{
    protected $fillable = ['short_id', 'url'];
    use HasFactory;

    function short()
    {
        return $this->belongsTo(Short::class);
    }
}
