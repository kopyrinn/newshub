<?php

namespace App\Observers;

use App\Models\Post;
use App\Notifications\AdminNotice;
use App\Notifications\ChannelNotification;

class PostObserver
{
    public function retrieved(Post $post)
    {
        $post->selected_categories = array_map('strval', $post->categories()->pluck('id')->toArray());
        $post->selected_rubrics = array_map('strval', $post->rubrics()->pluck('id')->toArray());
    }

    public function updating(Post $post)
    {
        if (!$post->slug) {
            $post->slug = \Str::slug($post->title, '-') . '-' . time();
        }

        if ($post->selected_categories) {
            $post->categories()->sync($post->selected_categories);
            unset($post->selected_categories);
        }

        if ($post->selected_rubrics) {
            $post->rubrics()->sync($post->selected_rubrics);
            unset($post->selected_rubrics);
        }
    }

    public function creating(Post $post)
    {
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
            $post->categories()->sync($post->selected_categories);
            unset($post->selected_categories);
        }

        if ($post->selected_rubrics) {
            $post->rubrics()->sync($post->selected_rubrics);
            unset($post->selected_rubrics);
        }

        if (auth()->user()->isModerator()) {
            $post->user_id = 1;
            $post->author_id = auth()->user()->id;
            $post->update();
        }

        $post->notify(new AdminNotice($post));
        $post->notify(new ChannelNotification($post));
    }
}
