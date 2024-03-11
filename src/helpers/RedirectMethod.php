<?php

namespace Contipay\Helpers;

use Contipay\Util\Reference;

class RedirectMethod
{
    protected int $merchantCode;
    protected string $webhookUrl;
    protected string $successUrl;
    protected string $cancelUrl;
    protected string $firstName;
    protected string $lastName;
    protected string $cell;
    protected string $email;
    protected string $middleName;
    protected string $nationalId;
    protected string $countryCode;
    protected float $amount;
    protected string $ref;
    protected string $currency;
    protected string $description;

    function __construct(int $merchantCode, string $webhookUrl, string $successUrl, string $cancelUrl)
    {
        $this->merchantCode = $merchantCode;
        $this->webhookUrl = $webhookUrl;
        $this->cancelUrl = $cancelUrl;
        $this->successUrl = $successUrl;
    }


    function setUpCustomer(string $firstName, string $lastName, string $cell, string $countryCode = 'ZW', string $email = "", string $middleName = "-", string $nationalId = "-")
    {
        $email = ($email == "") ? "$cell@contipay.co.zw" : $email;

        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->nationalId = $nationalId;
        $this->countryCode = $countryCode;
        $this->cell = $cell;

        return $this;
    }

    function setUpTransaction(float $amount, string $currency = 'ZWL', string $transactionRef = '', string $transactionDescription = '')
    {
        $ref = ($transactionRef == '') ? "V-" . (new Reference())->generate(8) : $transactionRef;
        $description = ($transactionDescription == '') ? 'Payment with ref:' . $ref : $transactionDescription;

        $this->amount = $amount;
        $this->currency = $currency;
        $this->ref = $ref;
        $this->description = $description;

        return $this;
    }

    function preparePayload(bool $isCoc = false, bool $isCod = false)
    {
        return
            [
                "reference" => $this->ref,
                'cod' => $isCod,
                'coc' => $isCoc,
                "description" => $this->description,
                "amount" => $this->amount,
                'customer' => array(
                    'firstName' => $this->firstName,
                    'surname' => $this->lastName,
                    'middleName' => $this->middleName,
                    "nationalId" => $this->nationalId,
                    'email' => $this->email,
                    'cell' => $this->cell,
                    'countryCode' => $this->countryCode,
                ),
                "currencyCode" => $this->currency,
                "merchantId" => $this->merchantCode,
                "webhookUrl" => $this->webhookUrl,
                'successUrl' => $this->successUrl,
                'cancelUrl' => $this->cancelUrl
            ];
    }
}