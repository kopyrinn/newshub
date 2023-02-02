<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class UserCategory extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    /**
     * @var  string
     */
    protected $table = 'user_categories';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getNameAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('name')['ru'])? $this->getTranslations('name')['ru']: "");
    }
}
