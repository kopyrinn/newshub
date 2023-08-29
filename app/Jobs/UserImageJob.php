<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use ImageOptimizer;

class UserImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (\Str::endsWith($this->user->avatar, '.svg') || $this->user->avatar === 'avatar.jpg') {
            $this->user->avatar_sm = $this->user->avatar;
            $this->user->update();
            return;
        }

        if (!Storage::disk('public')->exists($this->user->avatar)) return;

        if ($this->user->getRawOriginal('avatar_sm') && $this->user->avatar === $this->user->getRawOriginal('avatar_sm')) {
            return;
        }

        $manager = new ImageManager(['driver' => 'gd']);
        $name = \Str::uuid()->toString() . '.webp';

        $resize = $manager->make(Storage::disk('public')->path($this->user->avatar));
        $resize->orientate();

        // large
        $path = Storage::disk('public')->path("img/large");

        if ($resize->width() > 600) {
            $resize->fit(600);
        }

        $resize->save("{$path}/{$name}", 100);

        ImageOptimizer::optimize("{$path}/{$name}");

        $this->user->avatar = "img/large/{$name}";

        // small
        if ($resize->width() > 100) {
            $resize->fit(100);
        }

        $path = Storage::disk('public')->path("img/small");

        $resize->save("{$path}/{$name}", 100);
        $this->user->avatar_sm = "img/small/{$name}";

        $resize->destroy();

        ImageOptimizer::optimize("{$path}/{$name}");

        $this->user->update();
    }
}
