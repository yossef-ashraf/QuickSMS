<?php

namespace Fabrikar\Communication\Services;

use Fabrikar\Communication\Contracts\CommunicationInterface;
use Fabrikar\Communication\Traits\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NexmoWhatsappService implements CommunicationInterface
{
    use ResponseTrait;

    public function validate(array $params): bool
    {
        $rules = [
            'phone' => 'required|string',
            'message' => 'required|string'
        ];

        return Validator::make($params, $rules)->passes();
    }

    public function send(array $params): array
    {
        if (!$this->validate($params)) {
            return $this->errorResponse('Invalid WhatsApp parameters');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(
                    config('communication.sms.nexmo.api_key') . ':' . 
                    config('communication.sms.nexmo.api_secret')
                ),
                'Content-Type' => 'application/json'
            ])->post('https://api.nexmo.com/v1/messages', [
                'from' => [
                    'type' => 'whatsapp',
                    'number' => config('communication.sms.nexmo.sender')
                ],
                'to' => [
                    'type' => 'whatsapp',
                    'number' => $params['phone']
                ],
                'message' => [
                    'content' => [
                        'type' => 'text',
                        'text' => $params['message']
                    ]
                ],
                'channel' => 'whatsapp'
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                return $this->successResponse($responseData);
            } else {
                Log::error('Nexmo WhatsApp Send Failed', [
                    'response' => $responseData,
                    'status' => $response->status()
                ]);
                return $this->errorResponse('WhatsApp message sending failed');
            }
        } catch (\Exception $e) {
            Log::error('Nexmo WhatsApp Exception', [
                'message' => $e->getMessage()
            ]);
            return $this->errorResponse($e->getMessage());
        }
    }
}