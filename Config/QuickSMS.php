<?php

return [
    'default_provider' => env('SMS_DEFAULT_PROVIDER', 'cequens'),
    'default_type' => env('SMS_DEFAULT_TYPE', 'sms'),
    
    'sms' => [
        'cequens' => [
            'url' => env('CEQUENS_URL', 'https://apis.cequens.com/sms/v1/messages'),
            'token' => env('CEQUENS_TOKEN'),
            'sender' => env('CEQUENS_SENDER', 'Fabrikar'),
            'timeout' => env('CEQUENS_TIMEOUT', 10),
        ],
        
        'smsmisr' => [
            'url' => env('SMSMISR_URL', 'https://smsmisr.com/api/SMS'),
            'otp_url' => env('SMSMISR_OTP_URL', 'https://smsmisr.com/api/OTP'),
            'username' => env('SMSMISR_USERNAME'),
            'password' => env('SMSMISR_PASSWORD'),
            'environment' => env('SMSMISR_ENVIRONMENT', 1), // 1 for live, 2 for test
            'language' => env('SMSMISR_LANGUAGE', 1), // 1 for English, 2 for Arabic
            'template' => env('SMSMISR_TEMPLATE', ''),
            'sender' => env('SMSMISR_SENDER'),
            'timeout' => env('SMSMISR_TIMEOUT', 10),
        ],
        
        'viklink' => [
            'url' => env('VIKLINK_URL', 'https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMSWithUserSMSIdAndValidity'),
            'username' => env('VIKLINK_USERNAME'),
            'password' => env('VIKLINK_PASSWORD'),
            'sender' => env('VIKLINK_SENDER'),
            'default_lang' => env('VIKLINK_DEFAULT_LANG', 'E'), // E for English, A for Arabic
            'timeout' => env('VIKLINK_TIMEOUT', 15),
        ],
    ],
    
    'validation' => [
        'phone_regex' => '/^[+]?[0-9]{10,14}$/',
        'max_message_length' => 500,
    ],
];