<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Follower extends Pivot
{
    protected $table = 'followers';

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
