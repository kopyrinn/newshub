<?php

namespace App\Notifications;

use App\Helpers\Format;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;
use App\Channels\FcmChannel;

class ChannelNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram', FcmChannel::class];
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param  mixed  $notifiable
     * @param  string  $channel
     * @return bool
     */
    public function shouldSend($notifiable, $channel)
    {
        if ($channel == 'telegram') {
            return $this->post->to_telegram == 1 && $this->post->status == 1;
        } else if ($channel == FcmChannel::class) {
            return $this->post->to_fcm == 1 && $this->post->status == 1;
        }
    }

    public function toTelegram($notifiable)
    {
        if ($this->post->image && $this->post->image != 'news.jpg') {
            $message = TelegramFile::create()
                ->to(-1001343246474)
                ->content("*{$this->post->title}*\n\n{$this->post->summary}")
                ->photo(Format::thumb($this->post->image, 636, 442));
                // ->photo(asset("/storage/{$this->post->image}"));
        } else {
            $message = TelegramMessage::create()
                ->to(-1001343246474)
                ->content("*{$this->post->title}*");
        }

        $message->button('Читать далее ...', config('app.origin') . "/post/{$this->post->slug}");
        return $message;
    }

    public function toFcm($notifiable)
    {
        return $this->post;
    }
}
