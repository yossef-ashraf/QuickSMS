<?php

namespace QuickSMS;

use Illuminate\Support\ServiceProvider;
use QuickSMS\Services\{
    SmsService
};

class QuickSMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Correct the file path to include the directory separator
        $this->mergeConfigFrom(__DIR__.'/config/QuickSMS.php', 'QuickSMS'); // Updated line

        $this->app->bind(SmsService::class);
    }

    public function boot()
    {
        // Publish the config file when the application is running in the console
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/QuickSMS.php' => config_path('QuickSMS.php'), // Updated line
            ], 'QuickSMS-config');
        }
    }
}
