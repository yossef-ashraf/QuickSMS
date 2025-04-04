<?php

return [
    'sms' => [
        'cequens' => [
            'token' => env('CEQUENS_TOKEN'),
            'sender' => env('CEQUENS_SENDER', 'Fabrikar')
        ],
        'smsmisr' => [
            'username' => env('SMSMISR_USERNAME'),
            'password' => env('SMSMISR_PASSWORD'),
            'environment' => env('SMSMISR_ENVIRONMENT'),
            'language' => env('SMSMISR_LANGUAGE'),
            'template' => env('SMSMISR_TEMPLATE' , ''),
            'sender' => env('SMSMISR_SENDER')
        ],
        'viklink' => [
            'username' => env('VIKLINK_USERNAME'),
            'password' => env('VIKLINK_PASSWORD'),
            'sender' => env('VIKLINK_SENDER')
        ]
    ]
];