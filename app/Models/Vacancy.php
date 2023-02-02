<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use \DateTimeInterface;

class Vacancy extends Model
{
    use HasTranslations;

    public $translatable = ['job_title', 'requiremets', 'task', 'conditionsm'];

    /**
     * @var  string
     */
    protected $table = 'vacancies';

    protected $casts = [
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function getJobTitleAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('job_title')['ru'])? $this->getTranslations('job_title')['ru']: "");
    }

    public function getRequiremetsAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('requiremets')['ru'])? $this->getTranslations('requiremets')['ru']: "");
    }

    public function getTaskAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('task')['ru'])? $this->getTranslations('task')['ru']: "");
    }

    public function getConditionsmAttribute($value)
    {
        return $value?: (!empty($this->getTranslations('conditionsm')['ru'])? $this->getTranslations('conditionsm')['ru']: "");
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
