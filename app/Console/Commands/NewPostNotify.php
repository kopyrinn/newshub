<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Notifications\NewPost;

class NewPostNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:post:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New post notify';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Post::select('id', 'user_id', 'is_notified', 'title', 'slug')
            ->where('created_at', '<=', \Carbon\Carbon::now())
            ->where('is_notified', 0)
            ->where('status', 1)
            ->get();

        foreach ($posts as $post) {
            $post->is_notified = 1;
            $post->update();

            $user = $post->user;

            try {
                if ($user->followers()->exists()) {
                    foreach ($user->followers()->select('id')->get() as $follower) {
                        $follower->notify(new NewPost($post));
                    }
                }
            } catch (\Exception $e) {
                
            }
        }
    }
}
