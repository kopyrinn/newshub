<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use \DateTimeInterface;

class Category extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];

    /**
     * @var  string
     */
    protected $table = 'categories';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(static::class);
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class)
            ->using(CategoryPost::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'category_role', 'category_id', 'role_id');
    }

    public function hasSub()
    {
        return $this->categories()->where('show_on_menu', 1)->exists();
    }

    public function getSub()
    {
        return $this->categories()->where('show_on_menu', 1)->get();
    }

    public function getNameAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('name')['ru'])? $this->getTranslations('name')['ru']: "");
    }

    public function getDescriptionAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('description')['ru'])? $this->getTranslations('description')['ru']: "");
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->toIso8601String(); // 2019-02-01T03:45:27+00:00
    }
}
