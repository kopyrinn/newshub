<?php

namespace App\Observers;

use App\Models\Post;
use App\Notifications\AdminNotice;
use App\Notifications\ChannelNotification;
use Carbon\Carbon;

class PostObserver
{
    public function retrieved(Post $post)
    {
        $categories = $post->categories()->pluck('id')->flip();
        $post->selected_categories = !$categories? []: $categories->map(function($item) {
            return true;
        })->toArray();

        $rubrics = $post->rubrics()->pluck('id')->flip();
        $post->selected_rubrics = !$rubrics? []: $rubrics->map(function($item) {
            return true;
        })->toArray();
    }

    public function updating(Post $post)
    {
        if (!$post->slug) {
            $post->slug = \Str::slug($post->title, '-') . '-' . time();
        }

        if ($post->selected_categories) {
            $post->categories()->sync(array_keys(array_filter($post->selected_categories)));
            unset($post->selected_categories);
        }

        if ($post->selected_rubrics) {
            $post->rubrics()->sync(array_keys(array_filter($post->selected_rubrics)));
            unset($post->selected_rubrics);
        }
    }

    public function creating(Post $post)
    {
        if (!$post->created_at) {
            $post->created_at = Carbon::now();
        }

        if (!$post->event_date) {
            $post->event_date = Carbon::now();
        }

        if (!$post->slug) {
            $post->slug = \Str::slug($post->title, '-') . '-' . time();
        }

        if (!$post->uuid) {
            $post->uuid = \Str::uuid()->toString();
        }
    }

    public function created(Post $post)
    {
        if ($post->selected_categories) {
            $post->categories()->sync(array_keys(array_filter($post->selected_categories)));
            unset($post->selected_categories);
        }

        if ($post->selected_rubrics) {
            $post->rubrics()->sync(array_keys(array_filter($post->selected_rubrics)));
            unset($post->selected_rubrics);
        }

        $user = auth('sanctum')->user();

        if ($user && $user->isModerator()) {
            $post->user_id = 1;
            $post->author_id = $user->id;
            $post->update();
        }

        $post->notify(new AdminNotice($post));

        // if ($post->created_at <= Carbon::now() && $post->status == 1) {
        //     $post->notify(new ChannelNotification($post));
        // }
    }
}
