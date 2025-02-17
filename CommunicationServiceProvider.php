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
        // Correct the file path to include the directory separator
        $this->mergeConfigFrom(__DIR__.'/config/communication.php', 'communication'); // Updated line

        $this->app->bind(SmsService::class);
        // $this->app->bind(WhatsappService::class);
        // $this->app->bind(TelegramService::class);
        $this->app->bind(DiscordService::class);
    }

    public function boot()
    {
        // Publish the config file when the application is running in the console
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/communication.php' => config_path('communication.php'), // Updated line
            ], 'communication-config');
        }
    }
}
