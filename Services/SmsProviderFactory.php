<?php
namespace QuickSMS\Services;

use QuickSMS\Services\Providers\CequensProvider;
use QuickSMS\Services\Providers\SmsMisrProvider;
use QuickSMS\Services\Providers\ViklinkProvider;

class SmsProviderFactory {
    public static function create(string $provider) {
        return match($provider) {
            'cequens' => new CequensProvider(),
            'smsmisr' => new SmsMisrProvider(),
            'viklink' => new ViklinkProvider(),
            default => throw new \InvalidArgumentException('Unsupported provider')
        };
    }
}