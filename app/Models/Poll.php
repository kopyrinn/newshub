<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $casts = [
        'start_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $appends = [
        'read_mins',
    ];

    public function getReadMinsAttribute()
    {
        $length = substr_count(strip_tags(html_entity_decode($this->description)), ' ');
        return ceil($length / 190);
    }

    public function requests()
    {
        return $this->hasMany(PollRequest::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function getSummary($length = 200)
    {
        $summary = $this->summary? strip_tags(html_entity_decode($this->summary)): strip_tags(html_entity_decode($this->description));
        return \Str::limit($summary, $length);
    }
}
