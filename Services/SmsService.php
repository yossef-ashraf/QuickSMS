<?php
namespace QuickSMS\Services;

use QuickSMS\Contracts\QuickSMSInterface;
use QuickSMS\Traits\ResponseTrait;
use QuickSMS\Validators\SmsValidator;

class SmsService implements QuickSMSInterface {
    use ResponseTrait;

    public function __construct(
        private SmsValidator $validator
    ) {}

    public function send(array $params): array {
        if (!$this->validate($params)) {
            return $this->errorResponse('Invalid SMS parameters');
        }

        $provider = SmsProviderFactory::create($params['provider'] ?? 'cequens');
        
        try {
            $method = ($params['provider'] === 'smsmisr' && $params['type'] === 'otp')
                ? 'sendOtp'
                : 'sendSms';

            $response = $provider->{$method}($params);
            return $this->successResponse($response);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function validate(array $params): bool {
        return $this->validator->validate($params);
    }
}