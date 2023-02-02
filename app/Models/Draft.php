<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getContent()
    {
        return json_decode($this->content, true)?: [];
    }

    public function getContentValue($key, $default = null)
    {
        $content = $this->getContent();
        return isset($content[$key])? $content[$key]: $default;
    }
}
