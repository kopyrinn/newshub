<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use \DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $fillable = [
        'pageviews',
    ];

    protected function readMins(): Attribute
    {
        return Attribute::make(
            get: fn () => ceil(substr_count(strip_tags(html_entity_decode($this->content)), ' ') / 190),
        );
    }

    protected function imageMd(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function imageFit(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function imageSm(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function imageBlur(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['image'],
        );
    }

    protected function avatarSm(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: (isset($attributes['avatar'])? $attributes['avatar']: ''),
        );
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

    public function embedsSanitize()
    {
        foreach ($this->getTranslations('content') as $locale => $content) {
            if (!$content) continue;

            if (preg_match("@<iframe.*src=\"(https://www\.instagram\.com/(p|reel)/\w++/embed/).*</iframe>@Usi", $content)) {
                $content = preg_replace_callback("@<iframe.*src=\"(https://www\.instagram\.com/(p|reel)/\w++/embed/).*</iframe>@Usi", function($match) {
                    return "<a href=\"{$match[1]}\">{$match[1]}</a>";
                }, $content);
            }

            if (preg_match("@<iframe.*src=\"(https://www\.tiktok\.com/embed/v2/\d++).*</iframe>@Usi", $content)) {
                $content = preg_replace_callback("@<iframe.*src=\"(https://www\.tiktok\.com/embed/v2/\d++).*</iframe>@Usi", function($match) {
                    return "<a href=\"{$match[1]}\">{$match[1]}</a>";
                }, $content);
            }

            if (preg_match("@<iframe.*src=\"(https://t\.me/\w++/\d++).*</iframe>@Usi", $content)) {
                $content = preg_replace_callback("@<iframe.*src=\"(https://t\.me/\w++/\d++).*</iframe>@Usi", function($match) {
                    return "<a href=\"{$match[1]}\">{$match[1]}</a>";
                }, $content);
            }

            if (preg_match("@(<(iframe|embed).*</(iframe|embed)>)@Usi", $content)) {
                $content = preg_replace_callback("@(<(iframe|embed).*</(iframe|embed)>)@Usi", function($match) {
                    return htmlentities($match[1]);
                }, $content);
            }

            $this->setTranslation('content', $locale, $content);
        }
    }

    public function embedsParse()
    {
        foreach ($this->getTranslations('content') as $locale => $content) {
            if (preg_match("@(&lt;(script).*&lt;/(script)&gt;)@Usi", $content)) {
                $content = preg_replace("@(&lt;script.*&lt;/script&gt;)@Usi", '', $content);
            }

            // instagram
            if (preg_match("@&lt;blockquote.*data-instgrm-permalink=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/.*&lt;/blockquote&gt;@Usi", $content)) {
                $content = preg_replace_callback("@&lt;blockquote.*data-instgrm-permalink=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/.*&lt;/blockquote&gt;@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.instagram.com/{$match[1]}/{$match[2]}/embed/\"></iframe>";
                }, $content);
            }

            if (preg_match("@<a.*href=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/embed/.*</a>@Usi", $content)) {
                $content = preg_replace_callback("@<a.*href=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/embed/.*</a>@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.instagram.com/{$match[1]}/{$match[2]}/embed/\"></iframe>";
                }, $content);
            }

            if (preg_match("@<a.*href=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/.*</a>@Usi", $content)) {
                $content = preg_replace_callback("@<a.*href=\"https://www\.instagram\.com/(p|reel)/([0-9a-z-_]++)/.*</a>@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.instagram.com/{$match[1]}/{$match[2]}/embed/\"></iframe>";
                }, $content);
            }

            // tiktok
            if (preg_match("@<a.*href=\"https://www\.tiktok\.com/embed/v2/(\d++).*</a>@Usi", $content)) {
                $content = preg_replace_callback("@<a.*href=\"https://www\.tiktok\.com/embed/v2/(\d++).*</a>@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.tiktok.com/embed/v2/{$match[1]}\"></iframe>";
                }, $content);
            }

            if (preg_match("@<a.*href=\"https://www\.tiktok\.com/\@\w++/video/(\d++).*</a>@Usi", $content)) {
                $content = preg_replace_callback("@<a.*href=\"https://www\.tiktok\.com/\@\w++/video/(\d++).*</a>@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.tiktok.com/embed/v2/{$match[1]}\"></iframe>";
                }, $content);
            }

            if (preg_match("@&lt;blockquote.*cite=\"https://www\.tiktok\.com/\@\w++/video/(\d++).*&lt;/blockquote&gt;@Usi", $content)) {
                $content = preg_replace_callback("@&lt;blockquote.*cite=\"https://www\.tiktok\.com/\@\w++/video/(\d++).*&lt;/blockquote&gt;@Usi", function($match) {
                    return "<iframe frameborder=\"0\" scrolling=\"no\" width=\"100%\" height=\"600\" src=\"https://www.tiktok.com/embed/v2/{$match[1]}\"></iframe>";
                }, $content);
            }

            // iframes / embeds
            if (preg_match("@(&lt;(iframe|embed).*&lt;/(iframe|embed)&gt;)@Usi", $content)) {
                $content = preg_replace_callback("@(&lt;(iframe|embed).*&lt;/(iframe|embed)&gt;)@Usi", function($match) {
                    $embed = html_entity_decode($match[1]);
            
                    if (preg_match("@src=[\"']<a.*>.(.*)</a>[\"']@Usi", $embed)) {
                        $embed = preg_replace_callback("@src=[\"']<a.*>(.*)</a>[\"']@Usi", function($match) {
                            return "src=\"{$match[1]}\"";
                        }, $embed);
                    }
            
                    return $embed;
                }, $content);
            }

            // telegram
            if (preg_match("@<a.*href=\"(https://t\.me/\w++/\d++)\".*</a>@Usi", $content)) {
                $content = preg_replace_callback("@<a.*href=\"https://t\.me/(\w++/\d++)\".*</a>@Usi", function($match) {
                    $url = 'https://t.me/' . $match[1] . '?embed=1&color=0099FF';
                    $id = str_replace('/', '-', $match[1]);

                    return "<iframe id=\"{$id}\" frameborder=\"0\" scrolling=\"no\" width=\"100%\" src=\"{$url}\" class=\"telegram-post\"></iframe>";
                }, $content);
            }

            $this->setTranslation('content', $locale, $content);
        }
    }
}
