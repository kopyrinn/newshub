<?php

namespace App\Channels;

use App\Services\FirebaseAccessToken;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
            'message' => [
                'topic' => 'all',
                'notification' => [
                    'title' => $post->title,
                    'body' => $post->getSummary(55),
                    'image' => asset("storage/{$post->image_sm}"),
                ],
                // 'fcm_options' => [
                //     'link' => config('app.origin') . "/post/{$post->slug}",
                // ]
            ]
        ];

        $authKeyContent = json_decode(
            file_get_contents(Storage::path('newshub-328410-8828c1d2f287.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $bearerToken = app(FirebaseAccessToken::class)->get($authKeyContent);

        try {
            $response = Http::fcm()
                ->withToken($bearerToken)
                ->post('/messages:send', $payload);
        } catch (\Exception $e) {
            logger('fcm', [$e->getMessage()]);
        }
    }
}
