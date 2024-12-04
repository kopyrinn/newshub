<?php

namespace App\Notifications;

use App\Channels\DatabaseChannel;
use App\Channels\FcmSingleChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\App;

class NewPost extends Notification implements ShouldQueue
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
        return [DatabaseChannel::class, 'mail', FcmSingleChannel::class];
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
        if ($channel === FcmSingleChannel::class && !$notifiable->tokens()->whereIn('platform', ['android', 'ios'])->whereNotNull('app_token')->where('app_token', '!=', '0')->exists()) {
            return false;
        }

        return $this->post->status == 1;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('New Post') . ': ' . $this->post->title)
            ->action(__('Show'), config('app.origin') . "/post/{$this->post->slug}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'targetable' => $this->post,
        ];
    }

    public function toFcm($notifiable)
    {
        $tokens = $notifiable->tokens()
            ->select('app_token')
            ->whereIn('platform', ['android', 'ios'])
            ->whereNotNull('app_token')
            ->where('app_token', '!=', '0')
            ->groupBy('app_token')
            ->pluck('app_token');

        App::setLocale('ru');

        $messages = [];

        foreach ($tokens as $token) {
            
            $messages[] = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => __('New Post') . ': ' . $this->post->title,
                        'body' => $this->post->getSummary(100),
                        'image' => asset("storage/{$this->post->image_sm}"),
                    ],
                    // 'fcm_options' => [
                    //     'link' => config('app.origin') . "/post/{$post->slug}",
                    // ]
                ]
            ];

            // $messages[] = [
            //     'to' => $token,
            //     'notification' => [
            //         'body' => $this->post->getSummary(100),
            //         'title' => __('New Post') . ': ' . $this->post->title,
            //         'url' => config('app.origin') . "/post/{$this->post->slug}",
            //         'link' => config('app.origin') . "/post/{$this->post->slug}",
            //         'sound' => 'default',
            //         'content_available' => true,
            //         'image' => asset("storage/{$this->post->image_sm}"),
            //     ],
            // ];
        }

        return $messages;
    }
}
