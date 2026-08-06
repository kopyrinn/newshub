<?php

namespace App\Notifications;

use App\Helpers\Format;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class AdminNotice extends Notification implements ShouldQueue
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
        $this->onConnection('redis');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
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
        return $this->post->status == 0
            && $this->post->user
            && ! $this->post->user->roles()->whereIn('slug', ['moderator', 'admin'])->exists();
    }

    public function toTelegram($notifiable)
    {
        $user = $this->post->user->getName();
        $role = $this->post->user->roles()->first()->name;
        $content = \Str::limit(strip_tags(html_entity_decode($this->post->content)), 650);

        if ($this->post->image && $this->post->image != 'news.jpg') {
            $message = TelegramMessage::create()
                ->to(-767197089)
                ->content("Пользователь: {$user}\nРоль: {$role}\nКонтент: \n{$content}");
                //->photo(Format::thumb($this->post->image, 636, 442));
                // ->photo(asset("/storage/{$this->post->image}"));
        } else {
            $message = TelegramMessage::create()
                ->to(-767197089)
                ->content("Пользователь: {$user}\nРоль: {$role}\nКонтент: \n{$content}");
        }

        $message->button('Опубликовать', config('app.origin') . "/post/{$this->post->slug}/resolve")
            ->button('Подробнее', url("admin/resources/posts/{$this->post->id}"));

        return $message;
    }
}
