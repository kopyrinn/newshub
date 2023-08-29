<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function readMins(): Attribute
    {
        return Attribute::make(
            get: fn () => ceil(substr_count(strip_tags(html_entity_decode($this->description)), ' ') / 190),
        );
    }

    protected function imageMd(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function imageSm(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function imageBlur(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
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
