<?php

namespace Communication\Services;

use Communication\Contracts\CommunicationInterface;
use Communication\Traits\ResponseTrait;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

class DiscordService implements CommunicationInterface
{
    use ResponseTrait;

    protected $client;
    protected $baseUrl = 'https://discord.com/api/v10/channels/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function validate(array $params): bool
    {
        $rules = [
            'message' => 'required|string',
            'channel_id' => 'required|string',
            'token' => 'required|string'
        ];

        return Validator::make($params, $rules)->passes();
    }

    public function send(array $params): array
    {
        if (!$this->validate($params)) {
            return $this->errorResponse('Invalid Discord parameters');
        }

        try {
            $response = $this->client->post(
                $this->baseUrl . "{$params['channel_id']}/messages", 
                [
                    'headers' => [
                        'Authorization' => "Bot {$params['token']}",
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'content' => "The Laravel application: " . config('app.name') . "\n" . $params['message'],
                    ],
                ]
            );

            return $response->getStatusCode() === 200 
                ? $this->successResponse(null, 'Discord message sent successfully')
                : $this->errorResponse('Failed to send Discord message');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function sendEmbed(array $params): array
    {
        $rules = [
            'channel_id' => 'required|string',
            'token' => 'required|string',
            'embed' => 'required|array'
        ];

        if (!Validator::make($params, $rules)->passes()) {
            return $this->errorResponse('Invalid Discord embed parameters');
        }

        try {
            $response = $this->client->post(
                $this->baseUrl . "{$params['channel_id']}/messages", 
                [
                    'headers' => [
                        'Authorization' => "Bot {$params['token']}",
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'embeds' => [$params['embed']]
                    ],
                ]
            );

            return $response->getStatusCode() === 200 
                ? $this->successResponse(null, 'Discord embed sent successfully')
                : $this->errorResponse('Failed to send Discord embed');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}