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

    /**
     * Constructor for initializing class properties.
     *
     * @param int    $merchantCode  The merchant code.
     * @param string $webhookUrl    The URL for webhook notifications.
     * @param string $successUrl    The URL to redirect to upon successful transaction.
     * @param string $cancelUrl     The URL to redirect to upon canceled transaction.
     */
    public function __construct(int $merchantCode, string $webhookUrl, string $successUrl, string $cancelUrl)
    {
        $this->merchantCode = $merchantCode;
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * Set up customer details.
     *
     * @param string $firstName    The first name of the customer.
     * @param string $lastName     The last name of the customer.
     * @param string $cell         The cell number of the customer.
     * @param string $countryCode  The country code of the customer (default: 'ZW').
     * @param string $email        The email address of the customer (optional).
     * @param string $middleName   The middle name of the customer (default: "-").
     * @param string $nationalId   The national ID of the customer (default: "-").
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
     * Prepare payment payload for a transaction.
     *
     * @param bool $isCoc  Indicates if the transaction is Cash on Collection (default: false).
     * @param bool $isCod  Indicates if the transaction is Cash on Delivery (default: false).
     *
     * @return array The prepared payment payload.
     */
    public function preparePayload(bool $isCoc = false, bool $isCod = false): array
    {
        return [
            "reference" => $this->ref,
            'cod' => $isCod,
            'coc' => $isCoc,
            "description" => $this->description,
            "amount" => $this->amount,
            'customer' => [
                'firstName' => $this->firstName,
                'surname' => $this->lastName,
                'middleName' => $this->middleName,
                "nationalId" => $this->nationalId,
                'email' => $this->email,
                'cell' => $this->cell,
                'countryCode' => $this->countryCode,
            ],
            "currencyCode" => $this->currency,
            "merchantId" => $this->merchantCode,
            "webhookUrl" => $this->webhookUrl,
            'successUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl
        ];
    }
}
