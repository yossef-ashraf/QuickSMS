<?php
namespace QuickSMS\Services\Providers;

use Illuminate\Support\Facades\Http;

class SmsMisrProvider {
    public function sendSms(array $params): array {
        return Http::get(config('QuickSMS.sms.smsmisr.url'), [
            'environment' => config('QuickSMS.sms.smsmisr.environment'),
            'username' => config('QuickSMS.sms.smsmisr.username'),
            'password' => config('QuickSMS.sms.smsmisr.password'),
            'language' => config('QuickSMS.sms.smsmisr.language'),
            'sender' => config('QuickSMS.sms.smsmisr.sender'),
            'mobile' => $params['phone'],
            'message' => $params['message']
        ])->json();
    }

    public function sendOtp(array $params): array {
        return Http::post(config('QuickSMS.sms.smsmisr.otp_url'), [
            'environment' => config('QuickSMS.sms.smsmisr.environment'),
            'username' => config('QuickSMS.sms.smsmisr.username'),
            'password' => config('QuickSMS.sms.smsmisr.password'),
            'language' => config('QuickSMS.sms.smsmisr.language'),
            'sender' => config('QuickSMS.sms.smsmisr.sender'),
            'template' => config('QuickSMS.sms.smsmisr.template'),
            'mobile' => $params['phone'],
            'otp' => $params['message']
        ])->json();
    }
}