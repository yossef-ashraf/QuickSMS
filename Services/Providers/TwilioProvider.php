<?php

namespace QuickSMS\Services\Providers;

use Twilio\Rest\Client;

class TwilioProvider
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('QuickSMS.sms.twilio.sid'),
            config('QuickSMS.sms.twilio.token')
        );
    }

    public function sendSms(array $params): array
    {
        try {
            $message = $this->twilio->messages->create(
                $params['phone'],
                [
                    'from' => config('QuickSMS.sms.twilio.from'),
                    'body' => $params['message']
                ]
            );

            return [
                'sid' => $message->sid,
                'status' => $message->status,
                'provider' => 'twilio'
            ];

        } catch (\Exception $e) {
            throw new \Exception('Twilio SMS error: ' . $e->getMessage());
        }
    }
}