<?php
namespace QuickSMS\Services;

use QuickSMS\Services\Providers\CequensProvider;
use QuickSMS\Services\Providers\SmsMisrProvider;
use QuickSMS\Services\Providers\ViklinkProvider;
use QuickSMS\Services\Providers\TwilioProvider;

class SmsProviderFactory {
    public static function create(string $provider) {
        return match($provider) {
            'cequens' => new CequensProvider(),
            'smsmisr' => new SmsMisrProvider(),
            'viklink' => new ViklinkProvider(),
            'twilio' => new TwilioProvider(),
            default => throw new \InvalidArgumentException('Unsupported provider')
        };
    }
}