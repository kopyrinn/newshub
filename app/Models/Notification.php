<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;
use App\Notifications;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends DatabaseNotification
{
    protected $hidden = [
        'data',
        'type',
        'notifiable_id',
        'notifiable_type',
        'targetable_id',
        'targetable_type',
        'read_at',
        'updated_at',
    ];

    protected $appends = [
        'is_read',
        'title',
        'message',
        'url',
    ];

    public function getIsReadAttribute(): bool
    {
        return $this->read();
    }

    public function getItemAttribute(): mixed
    {
        return match($this->type) {
            Notifications\NewPost::class => Post::select('title', 'slug')->find($this->data['post_id']),
            Notifications\RejectPost::class => Post::select('title', 'slug')->find($this->data['post_id']),
            default => null
        };
    }

    public function getTitleAttribute(): string
    {
        try {
            return match($this->type) {
                Notifications\NewPost::class => __('New Post'),
                Notifications\MonthPackage::class => __('Packages'),
                Notifications\RejectPost::class => __('Post Rejected'),
                default => ''
            };
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getMessageAttribute(): string
    {
        try {
            return match($this->type) {
                Notifications\NewPost::class => $this->item->title,
                Notifications\MonthPackage::class => __('Your service package ends in 30 days. We recommend extending services in advance.'),
                Notifications\RejectPost::class => $this->item->title,
                default => ''
            };
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getUrlAttribute(): string
    {
        try {
            return match($this->type) {
                Notifications\NewPost::class => '/post/' . $this->item->slug,
                Notifications\MonthPackage::class => '/packages/' . $this->data['package'],
                Notifications\RejectPost::class => '/post/' . $this->item->slug,
                default => ''
            };
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\c
     */
    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }
}
