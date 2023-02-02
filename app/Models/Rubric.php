<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Rubric extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];

    /**
     * @var  string
     */
    protected $table = 'rubrics';

    protected $casts = [
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class)
            ->using(PostRubric::class);
    }

    public function getNameAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('name')['ru'])? $this->getTranslations('name')['ru']: "");
    }

    public function getDescriptionAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('description')['ru'])? $this->getTranslations('description')['ru']: "");
    }
}
