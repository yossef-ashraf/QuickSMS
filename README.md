# Laravel Communication Package

## Installation
```bash
composer require fabrikar/communication
```

## Configuration
```bash
php artisan vendor:publish --tag=communication-config
```

## Usage Examples

### SMS
```php
use Fabrikar\Communication\Services\SmsService;

$smsService = new SmsService();
$result = $smsService->send([
    'phone' => '1234567890',
    'message' => 'Hello',
    'provider' => 'cequens'
]);
```

### Whatsapp
```php
use Fabrikar\Communication\Services\WhatsappService;

$whatsappService = new WhatsappService();
$result = $whatsappService->send([
    'phone' => '1234567890',
    'message' => 'Hello'
]);
```

### Telegram
```php
use Fabrikar\Communication\Services\TelegramService;

$telegramService = new TelegramService();
$result = $telegramService->send([
    'chat_id' => '-1001234567890',
    'message' => 'Hello'
]);
```
use Fabrikar\Communication\Services\DiscordService;

$discordService = new DiscordService();
$result = $discordService->send([
    'channel_id' => 'your_channel_id',
    'token' => 'your_bot_token',
    'message' => 'Hello Discord!'
]);

// Sending an embed
$result = $discordService->sendEmbed([
    'channel_id' => 'your_channel_id',
    'token' => 'your_bot_token',
    'embed' => [
        'title' => 'Notification',
        'description' => 'Something important happened!',
        'color' => 3447003 // Blue color
    ]
]);


## License
MIT