<?php

use App\Helpers\Util;
use App\Models\Package;
use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use App\Notifications\ChannelNotification;
use App\Notifications\NewPost;
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
    $post = Post::find(12079);
    // $post->to_fcm = 1;
    // $post->notify(new ChannelNotification($post));
        
    $user = User::find(1);
    $user->notify(new NewPost($post));
});

Artisan::command('update:notifications', function () {
    $packages = Package::select('id', 'slug')->get();
    $notifications = \App\Models\Notification::whereNull('targetable_id')->get();

    foreach ($notifications as $notification) {
        if (isset($notification->data['post_id'])) {
            $notification->targetable_id = $notification->data['post_id'];
            $notification->targetable_type = Post::class;
        } else if (isset($notification->data['package'])) {
            $package = $packages->where('slug', $notification->data['package'])->first();
            $notification->targetable_id = $package->id;
            $notification->targetable_type = Package::class;
        } else {
            continue;
        }

        $notification->save();
    }
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
