<?php

namespace App\Jobs;

use App\Models\Poll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class PollImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $poll;

    /**
     * Create a new job instance.
     */
    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $hasPreviews = $this->poll->getRawOriginal('image_md') && $this->poll->getRawOriginal('image_sm') && $this->poll->getRawOriginal('image_blur');

        if (
            $hasPreviews &&
            (
                $this->poll->image === $this->poll->getRawOriginal('image_md') ||
                $this->poll->image === $this->poll->getRawOriginal('image_sm') ||
                $this->poll->image === $this->poll->getRawOriginal('image_blur')
            )
        ) {
            return;
        }

        if (!Storage::disk('public')->exists($this->poll->image)) return;

        // if (\Str::endsWith($this->poll->image, '.webp')) {
        //     ImageOptimizer::optimize(Storage::disk('public')->path($this->poll->image));
        // }

        $manager = new ImageManager(['driver' => 'gd']);

        $uuid = \Str::uuid()->toString();
        $name = $uuid . '.webp';

        $sizes = [
            "medium" => 750,
            "small" => 500,
            "blurry" => 300,
        ];

        $resize = $manager->make(Storage::disk('public')->path($this->poll->image));
        $resize->orientate();

        $w = $resize->width();

        $images = [];

        foreach ($sizes as $group => $dimension) {
            $path = Storage::disk('public')->path("img/{$group}");

            if ($w <= $dimension) {
                $images[$group] = $this->poll->image;

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
                ImageOptimizer::optimize("{$path}/{$name}");
            }

            $images[$group] = "img/{$group}/{$name}";
        }

        $resize->destroy();

        $hasUpdate = false;

        if (isset($images['medium'])) {
            $this->poll->image_md = $images['medium'];
            $hasUpdate = true;
        }

        if (isset($images['small'])) {
            $this->poll->image_sm = $images['small'];
            $hasUpdate = true;
        }

        if (isset($images['blurry'])) {
            $this->poll->image_blur = $images['blurry'];
            $hasUpdate = true;
        }

        if ($hasUpdate) {
            $this->poll->update();
        }
    }
}
