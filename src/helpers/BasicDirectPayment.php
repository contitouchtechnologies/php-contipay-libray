<?php
namespace Contipay\Helpers;

use Contipay\Util\Reference;

class BasicDirectPayment
{
    protected string $successUrl;
    protected string $cancelUrl;
    protected string $webhookUrl;
    protected string $providerName;
    protected string $providerCode;
    protected int $merchantId;

    public function __construct(string $webhookUrl, int $merchantId, string $successUrl = '', string $cancelUrl = '')
    {
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->merchantId = $merchantId;
    }

    function setUpProvider(string $providerName = 'Transfer', $providerCode = 'TF')
    {
        $this->providerName = $providerName;
        $this->providerCode = $providerCode;

        return $this;
    }

    function prepareBasic(float $amount, string $account, string $currency = 'ZWL', string $description = "", $ref = "", $cell = ""): array
    {
        $cell = ($cell == '') ? $account : $cell;

        $ref = ($ref == '') ? "R-" . (new Reference())->generate(8) : $ref;

        $description = ($description == '') ? 'Payment with ref:' . $ref : $description;

        return [
            "customer" => [
                "nationalId" => "-",
                "firstName" => $account,
                "middleName" => "-",
                "surname" => '-',
                "email" => "$account@contipay.co.zw",
                "cell" => $cell,
                "countryCode" => "ZW"
            ],
            "transaction" => [
                "providerCode" => $this->providerCode,
                "providerName" => $this->providerName,
                "amount" => $amount,
                "currencyCode" => $currency,
                "description" => $description,
                "webhookUrl" => $this->webhookUrl,
                "merchantId" => $this->merchantId,
                "reference" => ($ref == '') ? "" : ""
            ],
            "accountDetails" => [
                "accountNumber" => $account,
                "accountName" => '-',
                "accountExtra" => [
                    "smsNumber" => $cell,
                    "expiry" => "122021",
                    "cvv" => "003"
                ]
            ]
        ];
    }
}