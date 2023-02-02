<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostRubric extends Pivot
{
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function rubrics()
    {
        return $this->belongsTo(Rubric::class);
    }
}
