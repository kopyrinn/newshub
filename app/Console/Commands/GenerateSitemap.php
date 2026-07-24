<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Rubric;
use App\Models\Vacancy;
use App\Support\SitemapWriter;
use Illuminate\Console\Command;

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
        $origin = rtrim((string) config('app.origin'), '/');
        $sitemap = new SitemapWriter(base_path('dist/client/sitemap.xml'));

        $sitemap->add(
            "{$origin}/",
            now(),
            1,
            ['kk' => "{$origin}/kk/", 'en' => "{$origin}/en/"],
            'daily'
        );

        foreach (Category::all() as $category) {
            $sitemap->add(
                "{$origin}/category/{$category->slug}",
                $category->updated_at,
                0.8,
                [
                    'kk' => "{$origin}/kk/category/{$category->slug}",
                    'en' => "{$origin}/en/category/{$category->slug}",
                ]
            );

            foreach (Rubric::all() as $rubric) {
                $sitemap->add(
                    "{$origin}/category/{$category->slug}/{$rubric->slug}",
                    $rubric->updated_at,
                    0.8,
                    [
                        'kk' => "{$origin}/kk/category/{$category->slug}/{$rubric->slug}",
                        'en' => "{$origin}/en/category/{$category->slug}/{$rubric->slug}",
                    ]
                );
            }
        }

        foreach (Page::where('visibility', 1)->get() as $page) {
            $sitemap->add(
                "{$origin}/page/{$page->slug}",
                $page->updated_at,
                0.4,
                [
                    'kk' => "{$origin}/kk/page/{$page->slug}",
                    'en' => "{$origin}/en/page/{$page->slug}",
                ]
            );
        }

        foreach (Vacancy::where('status', 1)->get() as $vacancy) {
            $sitemap->add(
                "{$origin}/vacancy/{$vacancy->id}",
                $vacancy->updated_at,
                0.5,
                [
                    'kk' => "{$origin}/kk/vacancy/{$vacancy->id}",
                    'en' => "{$origin}/en/vacancy/{$vacancy->id}",
                ]
            );
        }

        foreach (Post::where('status', 1)->get() as $post) {
            $sitemap->add(
                "{$origin}/post/{$post->slug}",
                $post->updated_at,
                0.5,
                [
                    'kk' => "{$origin}/kk/post/{$post->slug}",
                    'en' => "{$origin}/en/post/{$post->slug}",
                ]
            );
        }

        $sitemap->finish();

        $this->info('Sitemap generated successfully.');

        return self::SUCCESS;
    }
}
