<?php

namespace App\Feeds;

use App\Models\Category;
use App\Models\CategoryPost;
use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use App\Traits\HasTranslations;

class Post extends Model implements Feedable
{
    use HasTranslations;

    public $translatable = ['title', 'summary', 'content'];

    public function toFeedItem(): FeedItem
    {
        $url = config('app.origin') . "/post/{$this->slug}";

        return FeedItem::create([
            'id' => $url,
            'title' => $this->title,
            'image' => asset("storage/{$this->image}"),
            'summary' => $this->summary ?: '',
            'updated' => $this->updated_at,
            'link' => $url,
            'authorName' => $this->author?->name ?? '',
            'authorEmail' => $this->author?->email ?? '',
            'category' => $this->categories()->first()?->name ?? '',
        ]);
    }

    public static function getFeedItems()
    {
        return Post::query()
            ->where('created_at', '<=', now())
            ->where('created_at', '>=', now()->subWeek())
            ->where('status', 1)
            ->with('author')
            ->get();
    }

    public function getImageAttribute($value)
    {
        return $value?: 'news.jpg';
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id', 'id');
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
}