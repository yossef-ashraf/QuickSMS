<?php

namespace Fabrikar\Communication\Services;

use Fabrikar\Communication\Contracts\CommunicationInterface;
use Fabrikar\Communication\Traits\ResponseTrait;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;

class WhatsappService implements CommunicationInterface
{
    use ResponseTrait;

    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('communication.whatsapp.twilio.sid'), 
            config('communication.whatsapp.twilio.token')
        );
    }

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
            return $this->errorResponse('Invalid Whatsapp parameters');
        }

        try {
            $message = $this->twilio->messages->create(
                "whatsapp:+{$params['phone']}",
                [
                    'from' => config('communication.whatsapp.twilio.from'),
                    'body' => $params['message']
                ]
            );

            return $this->successResponse([
                'sid' => $message->sid
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}