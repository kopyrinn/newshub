<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use \DateTimeInterface;

class Post extends Model
{
    use HasTranslations, Notifiable;

    public $translatable = ['title', 'summary', 'content'];

    public $selected_categories;
    public $selected_rubrics;

    protected $table = 'posts';

    protected $casts = [
        'event_date' => 'datetime',
        'content'    => CleanHtml::class,
    ];

    protected $appends = [
        'read_mins',
    ];

    public function getReadMinsAttribute()
    {
        $length = substr_count(strip_tags(html_entity_decode($this->content)), ' ');
        return ceil($length / 190);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(CategoryPost::class);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id', 'id');
    }

    public function rubrics()
    {
        return $this->belongsToMany(Rubric::class)
            ->using(PostRubric::class);
    }

    public function getFiles()
    {
        return is_string($this->files)? json_decode($this->files): $this->files;
    }

    public function isEvent()
    {
        return $this->categories()->where('slug', 'sobitiya')->exists();
    }

    public function getSummary($length = 200)
    {
        $summary = $this->summary? strip_tags(html_entity_decode($this->summary)): strip_tags(html_entity_decode($this->content));
        return Str::limit($summary, $length);
    }

    public function getContent($length = 200)
    {
        $content = $this->content? strip_tags(html_entity_decode($this->content)): '';
        return Str::limit($content, $length);
    }

    public function getTags()
    {
        return array_filter(array_map('trim', explode(",", $this->keywords)));
    }

    public function nextPost()
    {
        return self::select('slug')->where('id', '>', $this->id)->where('status', 1)->orderBy('id','asc')->first();
    }

    public function previousPost()
    {
        return self::select('slug')->where('id', '<', $this->id)->where('status', 1)->orderBy('id','desc')->first();
    }

    public function getImageAttribute($value)
    {
        return $value?: 'news.jpg';
    }

    public function getTitleAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $translations = $this->getTranslations('title');
        foreach (['kk', 'ru', 'en'] as $locale) {
            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }
        }
    }

    public function getSummaryAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $translations = $this->getTranslations('summary');
        foreach (['kk', 'ru', 'en'] as $locale) {
            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }
        }
    }

    public function getContentAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $translations = $this->getTranslations('content');
        foreach (['kk', 'ru', 'en'] as $locale) {
            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }
        }
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->toIso8601String(); // 2019-02-01T03:45:27+00:00
    // }
}
