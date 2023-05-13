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

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function packageFeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function getNameAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('name')['ru'])? $this->getTranslations('name')['ru']: "");
    }

    public function getContentAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('content')['ru'])? $this->getTranslations('content')['ru']: "");
    }
}
