<?php

namespace Contipay\Helpers;

use Contipay\Util\Reference;

class DirectMethod
{
    protected int $merchantCode;
    protected string $webhookUrl;
    protected string $providerName;
    protected string $providerCode;
    protected string $firstName;
    protected string $lastName;
    protected string $cell;
    protected string $email;
    protected string $middleName;
    protected string $nationalId;
    protected string $countryCode;
    protected string $account;
    protected string $accountName;
    protected string $accountExpiry;
    protected string $cvv;
    protected float $amount;
    protected string $ref;
    protected string $currency;
    protected string $description;

    function __construct(int $merchantCode, string $webhookUrl)
    {
        $this->merchantCode = $merchantCode;
        $this->webhookUrl = $webhookUrl;

    }

    function setUpProviders(string $providerName = 'Ecocash', string $providerCode = 'EC')
    {
        $this->providerCode = $providerCode;
        $this->providerName = $providerName;

        return $this;
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

    function setUpAccountDetails(string $account = '', string $accountName = '-', string $accountExpiry = '-', string $cvv = '')
    {
        $account = ($account == '') ? $this->cell : $account;

        $this->account = $account;
        $this->accountName = $accountName;
        $this->accountExpiry = $accountExpiry;
        $this->cvv = $cvv;

        return $this;
    }

    function preparePayload()
    {
        return [
            "customer" => [
                "nationalId" => $this->nationalId,
                "firstName" => $this->firstName,
                "middleName" => $this->middleName,
                "surname" => $this->lastName,
                "email" => $this->email,
                "cell" => $this->cell,
                "countryCode" => $this->countryCode
            ],
            "transaction" => [
                "providerCode" => $this->providerCode,
                "providerName" => $this->providerName,
                "amount" => $this->amount,
                "currencyCode" => $this->currency,
                "description" => $this->description,
                "webhookUrl" => $this->webhookUrl,
                "merchantId" => $this->merchantCode,
                "reference" => $this->ref
            ],
            "accountDetails" => [
                "accountNumber" => $this->account,
                "accountName" => $this->accountName,
                "accountExtra" => [
                    "smsNumber" => $this->cell,
                    "expiry" => $this->accountExpiry,
                    "cvv" => $this->cvv
                ]
            ]
        ];
    }
}