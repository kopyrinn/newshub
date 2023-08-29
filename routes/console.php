<?php

use App\Jobs\PollImageJob;
use App\Jobs\PostImageJob;
use App\Jobs\UserImageJob;
use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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
    // cwebp /www/wwwroot/hub.webartisan.space/storage/app/public/img/large/1RKpzklM3y8VeilJlnkQonHcMLcmkNFv5G9LYGD3.webp -o /www/wwwroot/hub.webartisan.space/storage/app/public/img/large/1RKpzklM3y8VeilJlnkQonHcMLcmkNFv5G9LYGD32.webp
    // dd(Storage::disk('public')->path('img/large/1RKpzklM3y8VeilJlnkQonHcMLcmkNFv5G9LYGD3.webp'));
    $post = Post::find(7709);
    PostImageJob::dispatch($post);

    // $users = User::select('id')->get();
    // foreach ($users as $user) {
    //     UserImageJob::dispatch($user);
    // }

    // $polls = Poll::select('id')->get();
    // foreach ($polls as $poll) {
    //     PollImageJob::dispatch($poll);
    // }

    // $posts = Post::select('id')->orderBy('created_at', 'desc')->get();
    // foreach ($posts as $post) {
    //     PostImageJob::dispatch($post);
    // }
});
