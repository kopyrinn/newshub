<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Package extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'content'];

    /**
     * @var  string
     */
    protected $table = 'packages';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getNameAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('name')['ru'])? $this->getTranslations('name')['ru']: "");
    }

    public function getContentAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('content')['ru'])? $this->getTranslations('content')['ru']: "");
    }
}
