<?php

namespace Communication;

use Illuminate\Support\ServiceProvider;
use Communication\Services\{
    SmsService,
    WhatsappService,
    TelegramService,
    DiscordService
};

class CommunicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/communication.php', 'communication');

        $this->app->bind(SmsService::class);
        // $this->app->bind(WhatsappService::class);
        // $this->app->bind(TelegramService::class);
        $this->app->bind(DiscordService::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/communication.php' => config_path('communication.php'),
        ], 'communication-config');
    }
}