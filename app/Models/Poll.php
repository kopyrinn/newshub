<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function requests()
    {
        return $this->hasMany(PollRequest::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }
}
