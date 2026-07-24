<?php

namespace App\Channels;

use App\Services\FirebaseAccessToken;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class FcmSingleChannel
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
        $messages = $notification->toFcm($notifiable);

        $authKeyContent = json_decode(
            file_get_contents(Storage::path('newshub-328410-8828c1d2f287.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $bearerToken = app(FirebaseAccessToken::class)->get($authKeyContent);

        // dump($messages);
        foreach ($messages as $message) {
            try {
                $response = Http::fcm()
                    ->withToken($bearerToken)
                    ->post('/messages:send', $message);
                $data = $response->json();

                logger('fcm', [$response->body()]);

                // if (isset($data['failure']) && $data['failure'] === 1) {
                //     $tokens = PersonalAccessToken::where('app_token', $message['to'])->get();

                //     if ($tokens->count()) {
                //         foreach ($tokens as $token) {
                //             $token->app_token = null;
                //             $token->platform = 'web';
                //             $token->update();
                //         }
                //     }

                //     \Log::error('FCM single failure', [$message, $response->body()]);
                // }
            } catch (\Exception $e) {
                \Log::error('FCM single error', [$e->getMessage()]);
            }
        }
    }
}
