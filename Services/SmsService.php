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

    protected function sendSmsViklink(array $params): array
    {
        $client = new Client();
        
        $data = [
            'UserName' => config('communication.sms.viklink.username'),
            'Password' => config('communication.sms.viklink.password'),
            'SMSText' => $params['message'],
            'SMSLang' => $params['lang'] ?? 'E',
            'SMSSender' => config('communication.sms.viklink.sender'),
            'SMSReceiver' => $params['phone'],
            'SMSID' => $this->uuidViklink()
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
    protected function uuidViklink()
    {
        $data = random_bytes(16);
        assert(strlen($data) == 16);
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        // Output the 36 character UUID.
        $data = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        return $data;
    }

    
    protected function sendSmsMisr(array $params): array
    {
        $response = Http::get($this->providers['smsmisr']['url'], [
            'environment' => config('communication.sms.smsmisr.environment'),
            'username' => config('communication.sms.smsmisr.username'),
            'password' => config('communication.sms.smsmisr.password'),
            'language' =>config('communication.sms.smsmisr.language'),
            'sender' => config('communication.sms.smsmisr.sender'),
            'mobile' => $params['phone'],
            'message' => $params['message']
        ]);

        return $response->json();
    }

    public function sendOTPMisr(array $params): array
    {
        $response = Http::post("https://smsmisr.com/api/OTP", [
            'environment' => config('communication.sms.smsmisr.environment'),
            'username' => config('communication.sms.smsmisr.username'),
            'password' => config('communication.sms.smsmisr.password'),
            'language' =>config('communication.sms.smsmisr.language'),
            'sender' => config('communication.sms.smsmisr.sender'),
            'template' => config('communication.sms.smsmisr.template'),
            'mobile' => $params['phone'],
            'otp' => $params['message']
        ]);

        return $response->json();
    }
}