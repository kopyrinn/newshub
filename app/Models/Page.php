<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description', 'page_content'];

    /**
     * @var  string
     */
    protected $table = 'pages';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getTitleAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('title')['ru'])? $this->getTranslations('title')['ru']: "");
    }

    public function getDescriptionAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('description')['ru'])? $this->getTranslations('description')['ru']: "");
    }

    public function getPageContentAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('page_content')['ru'])? $this->getTranslations('page_content')['ru']: "");
    }
}
