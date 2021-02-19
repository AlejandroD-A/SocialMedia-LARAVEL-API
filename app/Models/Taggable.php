<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taggable extends Pivot
{
    protected $table = 'taggables';
    protected $hidden = ['taggable_id', 'tag_id', 'created_at', 'updated_at'];

    public function taggable()
    {
        return $this->morphTo();
    }
}
