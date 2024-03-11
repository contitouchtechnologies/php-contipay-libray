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

    /**
     * Constructor for initializing class properties.
     *
     * @param int    $merchantCode  The merchant code.
     * @param string $webhookUrl    The URL for webhook notifications.
     */
    public function __construct(int $merchantCode, string $webhookUrl)
    {
        $this->merchantCode = $merchantCode;
        $this->webhookUrl = $webhookUrl;
    }

    /**
     * Set up payment provider details.
     *
     * @param string $providerName  The name of the payment provider (default: 'Ecocash').
     * @param string $providerCode  The code of the payment provider (default: 'EC').
     *
     * @return $this
     */
    public function setUpProviders(string $providerName = 'Ecocash', string $providerCode = 'EC'): self
    {
        $this->providerCode = $providerCode;
        $this->providerName = $providerName;

        return $this;
    }

    /**
     * Set up customer details.
     *
     * @param string $firstName   The first name of the customer.
     * @param string $lastName    The last name of the customer.
     * @param string $cell        The cell number of the customer.
     * @param string $countryCode The country code of the customer (default: 'ZW').
     * @param string $email       The email address of the customer (optional).
     * @param string $middleName  The middle name of the customer (default: "-").
     * @param string $nationalId  The national ID of the customer (default: "-").
     *
     * @return $this
     */
    public function setUpCustomer(
        string $firstName,
        string $lastName,
        string $cell,
        string $countryCode = 'ZW',
        string $email = "",
        string $middleName = "-",
        string $nationalId = "-"
    ): self {
        $this->email = ($email == "") ? "$cell@contipay.co.zw" : $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->nationalId = $nationalId;
        $this->countryCode = $countryCode;
        $this->cell = $cell;

        return $this;
    }

    /**
     * Set up transaction details.
     *
     * @param float  $amount                The amount of the transaction.
     * @param string $currency              The currency code (default: 'ZWL').
     * @param string $transactionRef        The reference for the transaction (optional).
     * @param string $transactionDescription The description for the transaction (optional).
     *
     * @return $this
     */
    public function setUpTransaction(
        float $amount,
        string $currency = 'ZWL',
        string $transactionRef = '',
        string $transactionDescription = ''
    ): self {
        $ref = ($transactionRef == '') ? "V-" . (new Reference())->generate(8) : $transactionRef;
        $description = ($transactionDescription == '') ? 'Payment with ref:' . $ref : $transactionDescription;

        $this->amount = $amount;
        $this->currency = $currency;
        $this->ref = $ref;
        $this->description = $description;

        return $this;
    }

    /**
     * Set up account details.
     *
     * @param string $account         The account number (optional, default: customer cell number).
     * @param string $accountName     The account name (default: '-').
     * @param string $accountExpiry   The account expiry date (default: '-').
     * @param string $cvv             The CVV (Card Verification Value) (default: '').
     *
     * @return $this
     */
    public function setUpAccountDetails(
        string $account = '',
        string $accountName = '-',
        string $accountExpiry = '-',
        string $cvv = ''
    ): self {
        $account = ($account == '') ? $this->cell : $account;

        $this->account = $account;
        $this->accountName = $accountName;
        $this->accountExpiry = $accountExpiry;
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Prepare payment payload for a transaction.
     *
     * @return array The prepared payment payload.
     */
    public function preparePayload(): array
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
