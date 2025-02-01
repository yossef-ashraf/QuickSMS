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
            'sender' => env('SMSMISR_SENDER')
        ],
        'viklink' => [
            'username' => env('VIKLINK_USERNAME'),
            'password' => env('VIKLINK_PASSWORD'),
            'sender' => env('VIKLINK_SENDER')
        ],
        'nexmo' => [
            'api_key' => env('NEXMO_API_KEY'),
            'api_secret' => env('NEXMO_API_SECRET'),
            'sender' => env('NEXMO_SENDER')
        ]
    ],
    'whatsapp' => [
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM')
        ],
        'gupshup' => [
            'api_key' => env('GUPSHUP_API_KEY'),
            'app_name' => env('GUPSHUP_APP_NAME')
        ]
    ],
    'telegram' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_url' => env('TELEGRAM_WEBHOOK_URL')
    ],
    'discord' => [
        'token' => env('DISCORD_TOKEN'),
        'channel_id' => env('DISCORD_CHANNEL_ID')
    ]
];