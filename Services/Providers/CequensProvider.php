<?php
namespace QuickSMS\Services\Providers;

use Illuminate\Support\Facades\Http;

class CequensProvider {
    public function send(array $params): array {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . config('QuickSMS.sms.cequens.token'),
            'Content-Type' => 'application/json'
        ])->post(config('QuickSMS.sms.cequens.url'), [
            'messageType' => 'text',
            'senderName' => config('QuickSMS.sms.cequens.sender'),
            'messageText' => $params['message'],
            'recipients' => $params['phone']
        ])->json();
    }
}