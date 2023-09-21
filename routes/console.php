<?php

use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('test', function () {
    $test = '<blockquote class="tiktok-embed" cite="https://www.tiktok.com/@dragon_knm1/video/7273129101358091525" data-video-id="7273129101358091525" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@dragon_knm1" href="https://www.tiktok.com/@dragon_knm1?refer=embed">@dragon_knm1</a> <p>Ахахах прости ПАП 🤣❤️</p> <a target="_blank" title="♬ оригинальный звук - Нургелды Dragon 🐉" href="https://www.tiktok.com/music/оригинальный-звук-7273129120946195206?refer=embed">♬ оригинальный звук - Нургелды Dragon 🐉</a> </section> </blockquote> <script async src="https://www.tiktok.com/embed.js"></script>';
    dd(htmlentities($test, ENT_NOQUOTES));

    $test = preg_replace_callback("@(&lt;(iframe|embed).*&lt;/(iframe|embed)&gt;)@Usi", function($match) {
        $embed = html_entity_decode($match[1]);

        if (preg_match("@src=[\"']<a.*>.(.*)</a>[\"']@Usi", $embed)) {
            $embed = preg_replace_callback("@src=[\"']<a.*>(.*)</a>[\"']@Usi", function($match) {
                return "src=\"{$match[1]}\"";
            }, $embed);
        }

        return $embed;
    }, $test);
    // preg_match_all(, $test, $matches);
    // dd($matches);
    dd($test);
});

Artisan::command('optimize:images', function () {

    $users = User::select('avatar', 'avatar_sm')->whereNotNull('avatar_sm')->get();
    foreach ($users as $i => $user) {
        if ($i % 100 === 0) {
            print 'users:' . ($i + 100) . PHP_EOL;
        }

        if (!\Str::endsWith($user->avatar_sm, '.webp') || !Storage::disk('public')->exists($user->avatar_sm) || $user->avatar === $user->avatar_sm) continue;
        $path = Storage::disk('public')->path($user->avatar);
        ImageOptimizer::optimize($path, $path);
        $path = Storage::disk('public')->path($user->avatar_sm);
        ImageOptimizer::optimize($path, $path);
    }

    $polls = Poll::select('image', 'image_md', 'image_sm')->whereNotNull('image_sm')->orderBy('created_at', 'desc')->get();
    foreach ($polls as $i => $poll) {
        if (!\Str::endsWith($poll->image_md, '.webp') || !Storage::disk('public')->exists($poll->image_md) || $poll->image === $poll->image_md) continue;
        $path = Storage::disk('public')->path($poll->image_md);
        ImageOptimizer::optimize($path, $path);

        if (!\Str::endsWith($poll->image_sm, '.webp') || !Storage::disk('public')->exists($poll->image_sm) || $poll->image === $poll->image_sm) continue;
        $path = Storage::disk('public')->path($poll->image_sm);
        ImageOptimizer::optimize($path, $path);
    }

    $posts = Post::select('image', 'image_md', 'image_sm', 'image_fit')->whereNotNull('image_sm')->orderBy('created_at', 'desc')->get();
    foreach ($posts as $i => $post) {
        if ($i % 100 === 0) {
            print 'posts:' . ($i + 100) . PHP_EOL;
        }

        if (!\Str::endsWith($post->image_fit, '.webp') || !Storage::disk('public')->exists($post->image_fit) || $post->image === $post->image_fit) continue;
        $path = Storage::disk('public')->path($post->image_fit);
        ImageOptimizer::optimize($path, $path);

        if (!\Str::endsWith($post->image_md, '.webp') || !Storage::disk('public')->exists($post->image_md) || $post->image === $post->image_md) continue;
        $path = Storage::disk('public')->path($post->image_md);
        ImageOptimizer::optimize($path, $path);

        if (!\Str::endsWith($post->image_sm, '.webp') || !Storage::disk('public')->exists($post->image_sm) || $post->image === $post->image_sm) continue;
        $path = Storage::disk('public')->path($post->image_sm);
        ImageOptimizer::optimize($path, $path);
    }
});
