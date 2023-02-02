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
        $sitemap = Sitemap::create(config('app.url'));

        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1)
                ->addAlternate("/kk/", 'kk')
                ->addAlternate("/en/", 'en')
        );

        foreach (Category::all() as $category) {
            $sitemap->add(
                Url::create("/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at)
                    ->setPriority(0.8)
                    ->addAlternate("/kk/category/{$category->slug}", 'kk')
                    ->addAlternate("/en/category/{$category->slug}", 'en')
            );

            foreach (Rubric::all() as $rubric) {
                $sitemap->add(
                    Url::create("/category/{$category->slug}/{$rubric->slug}")
                        ->setLastModificationDate($rubric->updated_at)
                        ->setPriority(0.8)
                        ->addAlternate("/kk/category/{$category->slug}/{$rubric->slug}", 'kk')
                        ->addAlternate("/en/category/{$category->slug}/{$rubric->slug}", 'en')
                );
            }
        }

        foreach (Page::where('visibility', 1)->get() as $page) {
            $sitemap->add(
                Url::create("/page/{$page->slug}")
                    ->setLastModificationDate($page->updated_at)
                    ->setPriority(0.4)
                    ->addAlternate("/kk/page/{$page->slug}", 'kk')
                    ->addAlternate("/en/page/{$page->slug}", 'en')
            );
        }

        foreach (Vacancy::where('status', 1)->get() as $vacancy) {
            $sitemap->add(
                Url::create("/vacancy/{$vacancy->id}")
                    ->setLastModificationDate($vacancy->updated_at)
                    ->setPriority(0.5)
                    ->addAlternate("/kk/vacancy/{$vacancy->id}", 'kk')
                    ->addAlternate("/en/vacancy/{$vacancy->id}", 'en')
            );
        }

        foreach (Post::where('status', 1)->get() as $post) {
            $sitemap->add(
                Url::create("/post/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.5)
                    ->addAlternate("/kk/post/{$post->slug}", 'kk')
                    ->addAlternate("/en/post/{$post->slug}", 'en')
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
