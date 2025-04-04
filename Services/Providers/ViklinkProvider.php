<?php
namespace QuickSMS\Services\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ViklinkProvider {
    public function send(array $params): array {
        $client = new Client();
        
        try {
            $response = $client->post(config('QuickSMS.sms.viklink.url'), [
                'form_params' => [
                    'UserName' => config('QuickSMS.sms.viklink.username'),
                    'Password' => config('QuickSMS.sms.viklink.password'),
                    'SMSText' => $params['message'],
                    'SMSLang' => $params['lang'] ?? 'E',
                    'SMSSender' => config('QuickSMS.sms.viklink.sender'),
                    'SMSReceiver' => $params['phone'],
                    'SMSID' => $this->generateUuid()
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new \Exception('Viklink SMS sending failed: ' . $e->getMessage());
        }
    }

    private function generateUuid(): string {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}