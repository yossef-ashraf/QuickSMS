<?php

namespace Fabrikar\Communication\Services;

use Fabrikar\Communication\Contracts\CommunicationInterface;
use Fabrikar\Communication\Traits\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TelegramService implements CommunicationInterface
{
    use ResponseTrait;

    protected $baseUrl = 'https://api.telegram.org/bot';

    public function validate(array $params): bool
    {
        $rules = [
            'chat_id' => 'required|string',
            'message' => 'required|string',
            'parse_mode' => 'in:HTML,Markdown,MarkdownV2'
        ];

        return Validator::make($params, $rules)->passes();
    }

    public function send(array $params): array
    {
        if (!$this->validate($params)) {
            return $this->errorResponse('Invalid Telegram parameters');
        }

        try {
            $response = Http::post($this->baseUrl . config('communication.telegram.token') . '/sendMessage', [
                'chat_id' => $params['chat_id'],
                'text' => $params['message'],
                'parse_mode' => $params['parse_mode'] ?? null
            ]);

            return $this->successResponse($response->json());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function sendPhoto(array $params): array
    {
        $rules = [
            'chat_id' => 'required|string',
            'photo' => 'required|url',
            'caption' => 'string'
        ];

        if (!Validator::make($params, $rules)->passes()) {
            return $this->errorResponse('Invalid Telegram photo parameters');
        }

        try {
            $response = Http::post($this->baseUrl . config('communication.telegram.token') . '/sendPhoto', [
                'chat_id' => $params['chat_id'],
                'photo' => $params['photo'],
                'caption' => $params['caption'] ?? null
            ]);

            return $this->successResponse($response->json());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}