<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
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

        foreach ($messages as $message) {
            try {
                $response = Http::fcm()->post('/send', $message);
                $data = $response->json();

                if (isset($data['failure']) && $data['failure'] === 1) {
                    $token = PersonalAccessToken::where('app_token', $message['to'])->first();

                    if ($token) {
                        $token->app_token = null;
                        $token->platform = 'web';
                        $token->update();
                    }

                    \Log::error('FCM single failure', [$message, $response->body()]);
                }
            } catch (\Exception $e) {
                \Log::error('FCM single error', [$e->getMessage()]);
            }
        }
    }
}