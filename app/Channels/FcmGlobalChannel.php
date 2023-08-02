<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class FcmGlobalChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $post = $notification->toFcm($notifiable);

        $payload = [
            'to'                    => '/topics/all',
            'collapse_key'          => 'type_a',
            'notification'          => [
                'title'                 => $post->title,
                'body'                  => $post->getSummary(55),
                'content_available'     => true,
                'android_channel_id'    => 'app_channel',
                'click_action'          => 'FLUTTER_NOTIFICATION_CLICK',
                'sound'                 => 'default',
                'image'                 => asset("storage/{$post->image}"),
            ],
            'priority'              => 'high',
            'data'                  => [
                'click_action'          => 'FLUTTER_NOTIFICATION_CLICK',
                'message'               => $post->getSummary(55),
                'post_type'             => 'article',
                'post_id'               => $post->id,
                'title'                 => $post->title,
                'image'                 => asset("storage/{$post->image}"),
                'url'                   => config('app.origin') . "/post/{$post->slug}",
                'show_in_notification'  => true,
                'dialog_title'          => $post->title,
                'dialog_text'           => $post->getSummary(100),
                'dialog_image'          => asset("storage/{$post->image}"),
                'sound'                 => 'default',
            ],
            'timeToLive'            => 10,
        ];

        try {
            Http::fcm()->post('/send', $payload);
        } catch (\Exception $e) {
            \Log::error('FCM Global', [$e->getMessage()]);
        }
    }
}