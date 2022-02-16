<!-- @format -->

# ContiPay PHP Payment Library V1.00

## Requirements

1 ContiPay Account

2 ContiPay Secret and Key

3 .env file with parameters below:

```
CP_TOKEN=key-here
CP_SECRET=secret-here
CP_DEV_URL=https://api2-test.contipay.co.zw
CP_LIVE_URL=https://api-v2.contipay.co.zw
CONTIPAY_MODE=DEV
```

## How it works

1 install with composer

```

```

2 require autoload file and create an instance of contitpay

```
<?php

use Nigel\ContipayDirect\ContiPay;

require_once __DIR__ . '/vendor/autoload.php';

$contipay = new ContiPay();

```

3 process payment

```
// payload example

$payload = [
    "customer" => [
        "nationalId" => "",
        "firstName" => "Test",
        "middleName" => "-",
        "surname" => "User",
        "email" => "test@test.co",
        "cell" => "26**0340**",
        "countryCode" => "ZW"
    ],
    "transaction" => [
        "providerCode" => "TF",
        "providerName" => "Zipit",
        "amount" =>  (float) 20,
        "currencyCode" => "ZWL",
        "description" => "Test",
        "webhookUrl" => "test.co",
        "successUrl" => "www.contipay.co.zw/success",
        "cancelUrl" => "www.contipay.co.zw/error",
        "merchantId" => {merchant},
        "reference" => "TS-123"
    ],
    "accountDetails" => [
        "accountNumber" => "0000",
        "accountName" => "test",
        "accountExtra" => [
            "smsNumber" => "26**0340**",
            "expiry" => "122021",
            "cvv" => "003"
        ]
    ]
];

// process payment

$contipay->processPayment($payload);
```
