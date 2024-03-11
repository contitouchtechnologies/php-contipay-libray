# ContiPay PHP Client 1.0.2 Documentation

## Requirements

1. ContiPay Account

2. ContiPay Secret and Key

## API URL

```markdown
UAT_URL = https://api2-test.contipay.co.zw
LIVE_URL = https://api-v2.contipay.co.zw
```

## How it Works

### 1. Installation with Composer

```bash
composer require jenesiszw/phone_lib:dev-master nigel/phone_lib:dev-master nigel/contipay-php:dev-main
```

### 2. Require Autoload File and Create an Instance of Contipay

```php
<?php

use JenesisZw\Phone;
use Contipay\Core\Contipay;
use Contipay\Helpers\DirectMethod;
use Contipay\Helpers\RedirectMethod;
use Contipay\Helpers\SimpleDirectMethod;
use Contipay\Helpers\SimpleRedirectMethod;

require_once __DIR__ . '/vendor/autoload.php';
```

### 3. Process Payment

#### i. Basic Direct Payment Example

```php
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);

$phone = (new Phone('0782000340'))->internationalFormat();

$payload = (new SimpleDirectMethod($merchantCode, $webhookUrl))
    ->setUpProvider('InnBucks', 'IB')
    ->preparePayload(
         $amount,
         $phone,
    );

$res = $contipay
    ->setAppMode("DEV")  // LIVE as another option
    ->setPaymentMethod()
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### ii. Basic Redirect Payment Example

```php
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);

$phone = (new Phone('0782000340'))->internationalFormat();

$payload = (
    new SimpleRedirectMethod(
        $merchantCode,
        $webhookUrl,
        $successUrl,
        $cancelUrl,
    )
)->preparePayload(1$amount, $phone);

$res = $contipay
    ->setAppMode("DEV")  // LIVE as another option
    ->setPaymentMethod("redirect")
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### iii. Direct Payment Example

```php
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);

$phone = (new Phone('0782000340'))->internationalFormat();

$payload = (
    new DirectMethod(
        $merchantCode,
        $webhookUrl
    )
)->setUpCustomer('Nigel', 'Jaure', $phone, 'ZW', 'nigeljaure@gmail.com')
    ->setUpProviders('Ecocash', 'EC')
    ->setUpAccountDetails($phone, 'Nigel Jaure')
    ->setUpTransaction($amount, "USD")
    ->preparePayload();

$res = $contipay
    ->setAppMode("DEV")
    ->setPaymentMethod()
    ->process($payload);


$res = $contipay
    ->setAppMode("DEV")  // LIVE as another option
    ->setPaymentMethod()
    ->process($payload);

header('Content-type: application/json');

echo $res;
```

#### iv. Redirect Payment Example

```php
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
);

$phone = (new Phone('0782000340'))->internationalFormat();

$payload = (
    new RedirectMethod(
        $merchantCode,
        $webhookUrl,
        $successUrl,
        $cancelUrl
    )
)->setUpCustomer('Nigel', 'Jaure', $phone, 'ZW', 'nigeljaure@gmail.com')
    ->setUpTransaction($amount, "USD")
    ->preparePayload();


$res = $contipay
    ->setAppMode("DEV")  // LIVE as another option
    ->setPaymentMethod("redirect")
    ->process($payload);

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
