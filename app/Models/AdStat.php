<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdStat extends Model
{
    protected $table = 'ad_stats';

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'clicks' => 'integer',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
