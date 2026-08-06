<?php

namespace App\Jobs;

use App\Helpers\Util;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class PostImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->onConnection('redis');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $disk = Storage::disk('public');

        if (! $this->post->image || ! $disk->exists($this->post->image)) {
            return;
        }

        $hasPreviews = $this->post->getRawOriginal('image_md') && $this->post->getRawOriginal('image_sm') && $this->post->getRawOriginal('image_blur');

        if (
            $hasPreviews &&
            (
                $this->post->image === $this->post->getRawOriginal('image_md') ||
                $this->post->image === $this->post->getRawOriginal('image_sm') ||
                $this->post->image === $this->post->getRawOriginal('image_blur')
            )
        ) {
            return;
        }

        $uuid = Util::uuidv5($this->post->image);
        $name = $uuid . '.webp';

        if (
            \Str::endsWith($this->post->image_md, $name) &&
            \Str::endsWith($this->post->image_sm, $name) &&
            \Str::endsWith($this->post->image_fit, $name) &&
            \Str::endsWith($this->post->image_blur, $name)
        ) {
            return;
        }

        // if (\Str::endsWith($this->post->image, '.webp')) {
        //     ImageOptimizer::optimize(Storage::disk('public')->path($this->post->image));
        // }

        $manager = new ImageManager(['driver' => 'gd']);

        $sizes = [
            "medium" => 750,
            "small" => 500,
            "blurry" => 300,
        ];

        $resize = $manager->make(Storage::disk('public')->path($this->post->image));
        $resize->orientate();

        $w = $resize->width();

        $images = [];

        foreach ($sizes as $group => $dimension) {
            $directory = "img/{$group}";
            $disk->makeDirectory($directory);
            $path = $disk->path($directory);

            if ($w <= $dimension) {
                $images[$group] = $this->post->image;

                continue;
            }

            $resize->resize($dimension, null, function ($const) {
                $const->aspectRatio();
            });

            if ($group === 'blurry') {
                $resize->blur(1);
                $resize->save("{$path}/{$name}", 1);
            } else {
                $resize->save("{$path}/{$name}", 95);
                // ImageOptimizer::optimize("{$path}/{$name}");
            }

            $images[$group] = "img/{$group}/{$name}";
        }

        $resize->destroy();

        $resize = $manager->make(Storage::disk('public')->path($this->post->image));
        $resize->orientate();
        $disk->makeDirectory('img/fit');
        $path = $disk->path('img/fit');
        $resize->fit($dimension);
        $resize->save("{$path}/{$name}", 95);
        // ImageOptimizer::optimize("{$path}/{$name}");
        $images['fit'] = "img/fit/{$name}";
        $resize->destroy();

        $hasUpdate = false;

        if (isset($images['medium'])) {
            $this->post->image_md = $images['medium'];
            $hasUpdate = true;
        }

        if (isset($images['fit'])) {
            $this->post->image_fit = $images['fit'];
            $hasUpdate = true;
        }

        if (isset($images['small'])) {
            $this->post->image_sm = $images['small'];
            $hasUpdate = true;
        }

        if (isset($images['blurry'])) {
            $this->post->image_blur = $images['blurry'];
            $hasUpdate = true;
        }

        if ($hasUpdate) {
            $this->post->update();
        }
    }
}
