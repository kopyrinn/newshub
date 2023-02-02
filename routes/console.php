<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test', function () {

    $category = \App\Models\Category::where('slug', 'sobitiya')
        ->first();

    abort_if(!$category, 404);

    $posts = $category->posts()
        ->select('title')
        ->where('status', 1)
        ->where('created_at', '<', \Carbon\Carbon::now())
        ->latest('created_at')
        ->groupBy('id')
        ->get();

    dd($posts->toArray());

});

Artisan::command('test-notify', function () {
    $post = \App\Models\Post::find(2251);
    $post->notify(new \App\Notifications\AdminNotice($post));
    // $path = \Illuminate\Support\Facades\Storage::disk('public')->exists('posts/2022/03/240322/6.jpeg');
    // ~rt(asset("/storage/{$post->image}"));
    $this->comment('ok');
});

Artisan::command('synclogs', function () {
    $users = \App\Models\User::get();

    foreach ($users as $user) {
        
    }

    $this->comment("done :)");
})->purpose('Display an inspiring quote');
