<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favouritable extends Model
{
    use HasFactory;


    public function favouritable()
    {
        return $this->morphTo();
    }
}
