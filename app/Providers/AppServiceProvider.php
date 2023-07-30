<?php

namespace App\Providers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Rubric;
use App\Models\UserCategory;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Laravel\Octane\Facades\Octane;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if ($this->app->environment('local')) {
        //     $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        //     $this->app->register(TelescopeServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Octane::tick('hub:config', function () {
            $categories = Category::select('name', 'slug', 'icon')
                ->orderBy('category_order')
                ->get();

            $rubrics = Rubric::select('name', 'slug')->get();

            $userCategories = UserCategory::select('name', 'slug', 'icon')
                ->selectRaw("(SELECT COUNT(*) FROM posts JOIN users ON users.id = posts.user_id AND users.user_category_id = user_categories.id JOIN role_user ON role_user.user_id = users.id JOIN roles ON roles.id = role_user.role_id WHERE roles.slug = 'press') as posts_count")
                ->withCount('users')
                ->get()
                ->map(function($item) {
                    list($icon, $layers) = explode(':', $item->icon);

                    $item->icon = $icon;
                    $item->layers = (int) $layers?: 0;

                    return $item;
                });

            $slides = Post::select('posts.id', 'posts.slug', 'posts.summary', 'posts.image', 'posts.created_at', 'posts.title')
                ->where('is_slider', 1)
                ->where('status', 1)
                ->where('created_at', '<', Carbon::now())
                ->orderBy('posts.created_at', 'DESC')
                ->limit(10)
                ->get();

            $featured = Post::select('posts.id', 'posts.slug', 'posts.summary', 'posts.image', 'posts.created_at', 'posts.title')
                ->where('is_featured', 1)
                ->where('status', 1)
                ->where('created_at', '<', Carbon::now())
                ->orderBy('posts.created_at', 'DESC')
                ->limit(4)
                ->get();

            $latest = Post::select('posts.id', 'posts.slug', 'posts.is_featured', 'posts.created_at', 'posts.title')
                ->where('posts.status', 1)
                ->join('category_post', 'category_post.post_id', 'posts.id')
                ->join('categories', 'categories.id', 'category_post.category_id')
                ->where('posts.created_at', '<', Carbon::now())
                ->where('categories.slug', 'news')
                ->orderBy('posts.created_at', 'DESC')
                ->groupBy('posts.id')
                ->take(25)
                ->get()
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                });

            $banners = Ad::select('image', 'location', 'url')
                ->where('expired_at', '>', Carbon::now())
                ->get()
                ->map(function($item) {
                    $item->url = ltrim(str_replace(config('app.origin_url'), '', $item->url), '/');
                    $item->url = ltrim(str_replace('https://newshub.kz', '', $item->url), '/');

                    if (!\Str::startsWith($item->url, 'http')) {
                        $item->url = '/' . $item->url;
                    }

                    return $item;
                })
                ->groupBy('location');

            $terms = Page::where('slug', 'terms-conditions')->first()->page_content;

            $lastVacancies = Vacancy::select('vacancies.id', 'vacancies.job_title', 'vacancies.task', 'vacancies.user_id', 'vacancies.created_at', 'users.name', 'users.avatar')
                ->join('users', 'users.id', 'vacancies.user_id')
                ->where('vacancies.status', 1)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $lastArticles = Post::select('posts.slug', 'posts.summary', 'posts.image', 'posts.created_at', 'posts.title', 'users.name', 'users.avatar')
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->where('posts.status', 1)
                ->where('posts.created_at', '<', Carbon::now())
                ->whereExists(function($query) {
                    $query->selectRaw(\DB::raw(1))
                        ->from('category_post')
                        ->whereColumn('category_post.post_id', 'posts.id')
                        ->where('category_post.category_id', 2);
                })
                ->inRandomOrder()
                ->take(3)
                ->get();

            $lastEvents = Post::select('posts.slug', 'posts.summary', 'posts.image', 'posts.created_at', 'posts.title', 'users.name', 'users.avatar')
                ->join('users', 'users.id', '=', 'posts.user_id')
                ->where('posts.status', 1)
                ->where('posts.created_at', '<', Carbon::now())
                ->whereExists(function($query) {
                    $query->selectRaw(\DB::raw(1))
                        ->from('category_post')
                        ->whereColumn('category_post.post_id', 'posts.id')
                        ->where('category_post.category_id', 8);
                })
                ->inRandomOrder()
                ->take(3)
                ->get();

            $tickers = Cache::get('tickers');

            foreach (['ru', 'kk', 'en'] as $locale) {
                App::setLocale($locale);

                Cache::set("hubconfig:{$locale}", [
                    'rubrics' => $rubrics->toArray(),
                    'categories' => $categories->toArray(),
                    'users' => $userCategories->toArray(),
                    'postSlides' => $slides->toArray(),
                    'postFeatured' => $featured->toArray(),
                    'postLatest' => $latest->toArray(),
                    'lastVacancies' => $lastVacancies->toArray(),
                    'lastArticles' => $lastArticles->toArray(),
                    'lastEvents' => $lastEvents->toArray(),
                    'banners' => $banners->toArray(),
                    'terms' => $terms,
                    'rates' => $tickers? $tickers: [
                        'USD' => [
                            'price' => 0,
                            'change' => 0,
                        ],
                        'EUR' => [
                            'price' => 0,
                            'change' => 0,
                        ],
                        'RUB' => [
                            'price' => 0,
                            'change' => 0,
                        ],
                        'CNY' => [
                            'price' => 0,
                            'change' => 0,
                        ]
                    ]
                ], 120);
            }
        })
        ->seconds(5)
        ->immediate();

    }
}
