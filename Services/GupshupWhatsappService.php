<?php

namespace Communication\Services;

use Communication\Contracts\CommunicationInterface;
use Communication\Traits\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GupshupWhatsappService implements CommunicationInterface
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
                'Authorization' => 'Bearer ' . config('communication.whatsapp.gupshup.api_key'),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post('https://api.gupshup.io/wa/api/v1/template/msg', [
                'channel' => 'whatsapp',
                'source' => config('communication.whatsapp.gupshup.app_name'),
                'destination' => $params['phone'],
                'template' => json_encode([
                    'id' => 'custom_message',
                    'params' => [$params['message']]
                ])
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                return $this->successResponse($responseData);
            } else {
                Log::error('Gupshup WhatsApp Send Failed', [
                    'response' => $responseData,
                    'status' => $response->status()
                ]);
                return $this->errorResponse('WhatsApp message sending failed');
            }
        } catch (\Exception $e) {
            Log::error('Gupshup WhatsApp Exception', [
                'message' => $e->getMessage()
            ]);
            return $this->errorResponse($e->getMessage());
        }
    }
}