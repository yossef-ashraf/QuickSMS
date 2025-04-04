<?php

namespace QuickSMS\Validators;

use Illuminate\Support\Facades\Validator;

class SmsValidator {
    public function validate(array $params): bool {
        $rules = [
            'phone' => 'required|regex:/^[+]?[0-9]{10,14}$/',
            'message' => 'required|string|max:500',
            'provider' => 'in:cequens,smsmisr,viklink',
            'type' => 'in:sms,otp'
        ];
        
        return Validator::make($params, $rules)->passes();
    }
}