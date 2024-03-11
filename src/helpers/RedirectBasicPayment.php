<?php

namespace Contipay\Helpers;

use Contipay\Util\Reference;

class RedirectBasicPayment
{
    protected string $successUrl;
    protected string $cancelUrl;
    protected string $webhookUrl;

    protected int $merchantId;

    public function __construct(string $webhookUrl, int $merchantId, string $successUrl, string $cancelUrl)
    {
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->merchantId = $merchantId;
    }

    function prepareBasic(float $amount, string $account, $ref = "", string $currency = 'USD', string $description = "", string $cell = "", bool $isCod = false, $isCoc = false): array
    {
        $cell = ($cell == '') ? $account : $cell;

        $ref = ($ref == '') ? "V-" . (new Reference())->generate(8) : $ref;

        $description = ($description == '') ? 'Payment with ref:' . $ref : $description;

        return array(
            "reference" => $ref,
            'cod' => $isCod,
            'coc' => $isCoc,
            "description" => $description,
            "amount" => $amount,

            'customer' => array(
                'firstName' => $account,
                'surname' => $account,
                'middleName' => '-',
                "nationalId" => '-',
                'email' => "$account@contipay.co.zw",
                'cell' => $cell,
                'countryCode' => 'zw',
            ),
            "currencyCode" => $currency,
            "merchantId" => $this->merchantId,
            "webhookUrl" => $this->webhookUrl,
            'successUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl
        );
    }
}