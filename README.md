<!-- @format -->

# ContiPay PHP Payment Library 1.0.0

## Requirements

1 ContiPay Account

2 ContiPay Secret and Key

3 .env file with parameters below:

```
CP_TOKEN=key-here
CP_SECRET=secret-here
CP_DEV_URL=https://api2-test.contipay.co.zw
CP_LIVE_URL=https://api-v2.contipay.co.zw
```

## How it works

1 install with composer

```
composer require nigel/contipay-php:dev-main

```

2 require autoload file and create an instance of contitpay

```
<?php

use ContiTouch\Contipay\ContiPay;

require_once __DIR__ . '/vendor/autoload.php';

$contipay = new Contipay(
    'token-here', // copy from .env or paste directly
    'secret-here', // copy from .env or paste directly
    'url-here' // copy from .env or paste directly
);


```

3 process payment

```
$payload = (
    new BasicDirectPayment(
        "www.contipay.co.zw/api/webhook",
        35
    )
)
    ->setUpProvider('Ecocash', 'EC')
    ->prepareBasic(
        100,
        (new Phone('0782000340'))->internationalFormat()
    );

    

$res = $contipay->setPaymentMethod('direct')->pay($payload);

header('Content-type: application/json');

echo $res;
```
