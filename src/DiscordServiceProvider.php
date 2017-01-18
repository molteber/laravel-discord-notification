<?php

namespace Puz\DiscordNotification;

use Illuminate\Support\ServiceProvider;

class DiscordServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $webhooks = $this->app->make('config')->get('services.discord.webhooks');

        $this->app->when(Discord::class)
            ->needs('$webhooks')
            ->give($webhooks);
    }
}
