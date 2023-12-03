<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class DatabaseChannel extends \Illuminate\Notifications\Channels\DatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification)
    {
        $data = $this->getData($notifiable, $notification);

        $params = [
            'id' => $notification->id,
            'type' => method_exists($notification, 'databaseType')
                ? $notification->databaseType($notifiable)
                : get_class($notification),
            'read_at' => null,
        ];

        if (isset($data['targetable'])) {
            $targetable = $data['targetable'];
            unset($data['targetable']);
    
            $params['targetable_type'] = get_class($targetable);
            $params['targetable_id'] = $targetable->id;
        }

        $params['data'] = $data;

        return $params;
    }

}
