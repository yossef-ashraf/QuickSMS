<?php
namespace Communication\Services;

use Communication\Contracts\CommunicationInterface;
use Communication\Traits\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SmsService implements CommunicationInterface
{
    use ResponseTrait;

    protected $providers = [
        'cequens' => [
            'url' => 'https://apis.cequens.com/sms/v1/messages',
            'method' => 'post'
        ],
        'smsmisr' => [
            'url' => 'https://smsmisr.com/api/SMS',
            'method' => 'get'
        ],
        'viklink' => [
            'url' => 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMSWithUserSMSIdAndValidity',
            'method' => 'post'
        ]
    ];

    public function validate(array $params): bool
    {
        $rules = [
            'phone' => 'required|regex:/^[+]?[0-9]{10,14}$/',
            'message' => 'required|string|max:500',
            'provider' => 'in:cequens,smsmisr,viklink',
            'type' => 'in:sms,otp'
        ];
        return Validator::make($params, $rules)->passes();
    }

    public function send(array $params): array
    {
        if (!$this->validate($params)) {
            Log::error('SMS Validation Failed', $params);
            return $this->errorResponse('Invalid SMS parameters');
        }

        $provider = $params['provider'] ?? 'cequens';
        $type = $params['type'] ?? 'sms';
        
        try {
            $response = match($provider) {
                'cequens' => $this->sendCequens($params),
                'smsmisr' => $type === 'otp' 
                    ? $this->sendOTPMisr($params)
                    : $this->sendSmsMisr($params),
                'viklink' => $this->sendSmsViklink($params),
                default => throw new \Exception('Unsupported SMS provider')
            };
            return $this->successResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    protected function sendCequens(array $params): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('communication.sms.cequens.token'),
            'Content-Type' => 'application/json'
        ])->post($this->providers['cequens']['url'], [
            'messageType' => 'text',
            'senderName' => config('communication.sms.cequens.sender'),
            'messageText' => $params['message'],
            'recipients' => $params['phone']
        ]);

        return $response->json();
    }

    protected function sendSmsMisr(array $params): array
    {
        $response = Http::get($this->providers['smsmisr']['url'], [
            'username' => config('communication.sms.smsmisr.username'),
            'password' => config('communication.sms.smsmisr.password'),
            'sender' => config('communication.sms.smsmisr.sender'),
            'mobile' => $params['phone'],
            'message' => $params['message']
        ]);

        return $response->json();
    }

    protected function sendSmsViklink(array $params): array
    {
        $client = new Client();
        
        $data = [
            'UserName' => config('communication.sms.viklink.username'),
            'Password' => config('communication.sms.viklink.password'),
            'SMSText' => $params['message'],
            'SMSLang' => 'E',
            'SMSSender' => config('communication.sms.viklink.sender'),
            'SMSReceiver' => $params['phone'],
            'SMSID' => Str::uuid()->toString()
        ];

        try {
            $response = $client->post($this->providers['viklink']['url'], [
                'form_params' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new \Exception('Viklink SMS sending failed: ' . $e->getMessage());
        }
    }

    public function sendOTPMisr(array $params): array
    {
        $response = Http::post("https://smsmisr.com/api/OTP", [
            'username' => config('communication.sms.smsmisr.username'),
            'password' => config('communication.sms.smsmisr.password'),
            'sender' => config('communication.sms.smsmisr.sender'),
            'mobile' => $params['phone'],
            'otp' => $params['message']
        ]);

        return $response->json();
    }
}