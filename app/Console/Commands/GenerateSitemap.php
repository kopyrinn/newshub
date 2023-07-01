<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Rubric;
use App\Models\Vacancy;
use Illuminate\Console\Command;
// use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Sitemap;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create(config('app.origin'));

        $sitemap->add(
            Url::create(config('app.origin') . '/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1)
                ->addAlternate(config('app.origin') . "/kk/", 'kk')
                ->addAlternate(config('app.origin') . "/en/", 'en')
        );

        foreach (Category::all() as $category) {
            $sitemap->add(
                Url::create(config('app.origin') . "/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority(0.8)
                    ->addAlternate(config('app.origin') . "/kk/category/{$category->slug}", 'kk')
                    ->addAlternate(config('app.origin') . "/en/category/{$category->slug}", 'en')
            );

            foreach (Rubric::all() as $rubric) {
                $sitemap->add(
                    Url::create(config('app.origin') . "/category/{$category->slug}/{$rubric->slug}")
                        ->setLastModificationDate($rubric->updated_at)
                        ->setPriority(0.8)
                        ->addAlternate(config('app.origin') . "/kk/category/{$category->slug}/{$rubric->slug}", 'kk')
                        ->addAlternate(config('app.origin') . "/en/category/{$category->slug}/{$rubric->slug}", 'en')
                );
            }
        }

        foreach (Page::where('visibility', 1)->get() as $page) {
            $sitemap->add(
                Url::create(config('app.origin') . "/page/{$page->slug}")
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.4)
                    ->addAlternate(config('app.origin') . "/kk/page/{$page->slug}", 'kk')
                    ->addAlternate(config('app.origin') . "/en/page/{$page->slug}", 'en')
            );
        }

        foreach (Vacancy::where('status', 1)->get() as $vacancy) {
            $sitemap->add(
                Url::create(config('app.origin') . "/vacancy/{$vacancy->id}")
                    ->setLastModificationDate($vacancy->updated_at)
                    ->setPriority(0.5)
                    ->addAlternate(config('app.origin') . "/kk/vacancy/{$vacancy->id}", 'kk')
                    ->addAlternate(config('app.origin') . "/en/vacancy/{$vacancy->id}", 'en')
            );
        }

        foreach (Post::where('status', 1)->get() as $post) {
            $sitemap->add(
                Url::create(config('app.origin') . "/post/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.5)
                    ->addAlternate(config('app.origin') . "/kk/post/{$post->slug}", 'kk')
                    ->addAlternate(config('app.origin') . "/en/post/{$post->slug}", 'en')
            );
        }

        $sitemap->writeToFile(base_path('dist/client/sitemap.xml'));
    }
}
