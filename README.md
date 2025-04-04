# Laravel QuickSMS Package

## 📦 Installation

```bash
composer require quickhelper/quicksms
```

## ⚙️ Configuration

Publish the config file:
```bash
php artisan vendor:publish --provider="QuickSMS\QuickSMSServiceProvider" --tag="config"
```

## 🔧 Environment Variables (.env)

```env
# QuickSMS Defaults
QUICKSMS_DEFAULT_PROVIDER=cequens
QUICKSMS_DEFAULT_TYPE=sms

# Cequens Provider
QUICKSMS_CEQUENS_URL=https://apis.cequens.com/sms/v1/messages
QUICKSMS_CEQUENS_TOKEN=your_api_token_here
QUICKSMS_CEQUENS_SENDER=Fabrikar
QUICKSMS_CEQUENS_TIMEOUT=10

# SMSMisr Provider
QUICKSMS_SMSMISR_URL=https://smsmisr.com/api/SMS
QUICKSMS_SMSMISR_OTP_URL=https://smsmisr.com/api/OTP
QUICKSMS_SMSMISR_USERNAME=your_username
QUICKSMS_SMSMISR_PASSWORD=your_password
QUICKSMS_SMSMISR_SENDER=your_sender_id
QUICKSMS_SMSMISR_ENVIRONMENT=1  # 1=live, 2=test
QUICKSMS_SMSMISR_LANGUAGE=1     # 1=English, 2=Arabic

# Viklink Provider
QUICKSMS_VIKLINK_URL=https://smsvas.vlserv.com/VLSMSPlatformResellerAPI/NewSendingAPI/api/SMSSender/SendSMSWithUserSMSIdAndValidity
QUICKSMS_VIKLINK_USERNAME=your_username
QUICKSMS_VIKLINK_PASSWORD=your_password
QUICKSMS_VIKLINK_SENDER=your_sender_id
QUICKSMS_VIKLINK_DEFAULT_LANG=E  # E=English, A=Arabic
```

## 🚀 Basic Usage

### Sending 
```php
use QuickSMS\Services\SmsService;
use QuickSMS\Validators\SmsValidator;

$smsService = new SmsService(new SmsValidator());
$result = $smsService->send([
    'phone' => '+2010000000',
    'message' => 'Test message',
    'provider' => 'cequens',
    'type' => 'sms'
]);

$smsService->send([
    'phone' => $phone,
    'message' => $otpCode, 
    'type' => 'otp',
    'provider' => 'smsmisr',
    'template' => env('QUICKSMS_SMSMISR_OTP_TEMPLATE') 
]);

```


## 🔍 Response Structure
All methods return a standardized response array:
```php
[
    'success' => true|false,
    'data' => [...], // Provider response data
    'message' => 'Status message',
    'provider' => 'provider_name'
]
```

## 📜 License
MIT Licensed


Best regards,  
[Yossef Ashraf](https://github.com/yossef-ashraf)

