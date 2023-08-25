<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

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
                \Log::error('FCM Message', [$response->body()]);
            } catch (\Exception $e) {
                \Log::error('FCM Single', [$e->getMessage()]);
            }
        }
    }
}