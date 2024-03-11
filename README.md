<!-- @format -->

# ContiPay PHP Payment Library 1.0.1

## Requirements

1. ContiPay Account

2. ContiPay Secret and Key

## API URL

```
UAT_URL = https://api2-test.contipay.co.zw
LIVE_URL = https://api-v2.contipay.co.zw
```

## How it works

1. install with composer

```
composer require jenesiszw/phone_lib:dev-master nigel/phone_lib:dev-master nigel/contipay-php:dev-main

```

2. require autoload file and create an instance of contitpay

```
<?php

use JenesisZw\Phone;
use Contipay\Core\Contipay;
use Contipay\Helpers\BasicDirectPayment;
use Contipay\Helpers\RedirectBasicPayment;

require_once __DIR__ . '/vendor/autoload.php';

```

3. process payment

`i.` Basic Direct Payment Example

```
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
    'url-here' // copy from .env or paste directly
);


$payload = (
    new BasicDirectPayment(
        $mechantCode, // replace with merchant code
        "https://www.contipay.co.zw/api/webhook", // webhook url
    )
)
    ->setUpProvider('InnBucks', 'IB')
    ->prepareBasic(
        100,
        (new Phone('0782000340'))->internationalFormat()
    );



$res = $contipay->setPaymentMethod('direct')->pay($payload);

header('Content-type: application/json');

echo $res;
```

`ii.` Basic Redirect Payment Example

```
$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
    'url-here' // copy from .env or paste directly
);


$payload = (
    new RedirectBasicPayment(
        $mechantCode, // replace with merchant code
        "https://www.contipay.co.zw/api/webhook", // webhook url
        "https://www.contipay.co.zw/api/success", // success url
        "https://www.contipay.co.zw/api/cancel",  // cancel url
    )
)
    ->prepareBasic(
        10,
        (new Phone('0782000340'))->internationalFormat()
    );



$res = $contipay->setPaymentMethod('direct')->pay($payload);

header('Content-type: application/json');

echo $res;
```
