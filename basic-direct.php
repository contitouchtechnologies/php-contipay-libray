<?php

use Contipay\Core\Contipay;
use Contipay\Helpers\BasicDirectPayment;

require_once './app/bootstrap.php';

$contipay = new Contipay(
    'token-here',
    'secret-here',
    'url-here'
);

$payload = (
    new BasicDirectPayment(
        "www.contipay.co.zw/api/webhook",
        35
    )
)
    ->setUpProvider()
    ->prepareBasic(100, '263782000340');

    

$res = $contipay->setPaymentMethod('direct')->pay($payload);

header('Content-type: application/json');

echo $res;