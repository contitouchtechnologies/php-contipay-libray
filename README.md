# ContiPay PHP Client Documentation

## Requirements

1. ContiPay Account

2. ContiPay Secret and Key

## How it Works

### 1. Install latest with Composer

```bash
composer require nigel/contipay-php
```

### 2. Require Autoload File and Classes Imports

```php
<?php
use Contipay\Core\Contipay;
use Contipay\Helpers\Payload\PayloadGenerator;

require_once __DIR__ . '/vendor/autoload.php';


$webhookUrl = "https://www.contipay.co.zw/api/webhook";
$successUrl = "https://www.contipay.co.zw/api/success";
$cancelUrl = "https://www.contipay.co.zw/api/cancel";
$merchantCode = 00;
$phone = "2637****340";
$amount = (float) 10;

$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);
```

### 3. Process Payment

#### i. Basic Direct Payment Example

```php

$payload = (new PayloadGenerator($merchantCode, $webhookUrl))
    ->setUpProviders('InnBucks', 'IB')
    ->simpleDirectPayload(
        $amount,
        $phone,
    );

$res = $contipay->setAppMode("DEV")
    ->setPaymentMethod()
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### ii. Basic Redirect Payment Example

```php

$payload = (
    new PayloadGenerator(
        $merchantCode,
        $webhookUrl,
        $successUrl,
        $cancelUrl
    )
)->simpleRedirectPayload(
        $amount,
        $phone
    );

$res = $contipay->setAppMode("DEV")
    ->setPaymentMethod('redirect')
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### iii. Direct Payment Example

```php

$payload = (
    new PayloadGenerator(
        $merchantCode,
        $webhookUrl
    )
)->setUpCustomer('Nigel', 'Jaure', $phone, 'ZW', 'nigeljaure@gmail.com')
    ->setUpProviders('Ecocash', 'EC')
    ->setUpAccountDetails($phone, 'Nigel Jaure')
    ->setUpTransaction($amount, "USD")
    ->directPayload();

$res = $contipay
    ->setAppMode("DEV")
    ->setPaymentMethod()
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### iv. Redirect Payment Example

```php
$payload = (
    new PayloadGenerator(
        $merchantCode,
        $webhookUrl,
        $successUrl,
        $cancelUrl
    )
)->setUpCustomer('Nigel', 'Jaure', $phone, 'ZW', 'nigeljaure@gmail.com')
    ->setUpTransaction($amount, "USD")
    ->redirectPayload();

$res = $contipay->setAppMode("DEV")
    ->setPaymentMethod('redirect')
    ->process($payload);

header('Content-type: application/json');

echo $res;;
```

### 4. Disburse Payment

```php

$privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
     YOUR KEY HERE 
-----END PRIVATE KEY-----
EOD;

$payload = (
    new PayloadGenerator(
        $merchantCode,
        $webhookUrl
    )
)->setUpCustomer('Nigel', 'Jaure', $phone, 'ZW', 'nigeljaure@gmail.com')
    ->setUpProviders('Transfer', 'TF')
    ->setUpAccountDetails($phone, 'Nigel Jaure')
    ->setUpTransaction($amount, "USD")
    ->directPayload();

$res = $contipay
    ->setAppMode("DEV")
    ->setPaymentMethod()
    ->disburse($payload, $privateKey);

header('Content-type: application/json');

echo $res;
```

## Additional Notes

- The `updateURL` method is optional and applicable only if the URL has changed. Use it to update the URLs accordingly. Here's how you can use it:

```php
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);

// Update URLs if necessary
$contipay->updateURL('dev-url', 'live-url');

// Process payment with the updated URLs
$res = $contipay
    ->setAppMode("DEV")  // LIVE as another option
    ->setPaymentMethod()
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

- Ensure to set the appropriate mode (`DEV` or `LIVE`) using the `setAppMode` method before processing payments.

- The provided examples cover basic scenarios, including direct and redirect payment methods, customer information setup, and transaction details.

- ContiPay JavaScript Alternative [here](https://github.com/njzw/contipay-js-client)
