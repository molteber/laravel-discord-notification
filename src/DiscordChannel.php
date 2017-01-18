<?php

namespace Puz\DiscordNotification;

use Illuminate\Notifications\Notification;

class DiscordChannel
{
    protected $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$channel = $notifiable->routeNotificationFor('discord')) {
            return;
        }

        $message = $notification->toDiscord($notifiable);

        $this->discord->send($message);
    }
}
