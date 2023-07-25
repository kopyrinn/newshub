<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $casts = [
        'roles' => 'array',
        'packages' => 'array',
        'activity' => 'boolean',
        'start_at' => 'datetime',
        'ends_at' => 'datetime',
        'total' => 'integer',
        'sent' => 'integer',
    ];

    protected $fillable = [
        'start_at',
        'ends_at',
        'total',
        'sent',
    ];
}
