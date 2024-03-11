<?php

namespace Contipay\Helpers;

use Contipay\Util\Reference;

class SimpleDirectMethod
{
    protected string $successUrl;
    protected string $cancelUrl;
    protected string $webhookUrl;
    protected string $providerName;
    protected string $providerCode;
    protected int $merchantId;

    /**
     * Constructor for initializing class properties.
     *
     * @param int    $merchantId  The merchant ID.
     * @param string $webhookUrl  The URL for webhook notifications.
     * @param string $successUrl  The URL to redirect to upon successful transaction.
     * @param string $cancelUrl   The URL to redirect to upon canceled transaction.
     */
    public function __construct(int $merchantId, string $webhookUrl, string $successUrl = '', string $cancelUrl = '')
    {
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->merchantId = $merchantId;
    }

    /**
     * Set up the provider name and code.
     *
     * @param string $providerName The name of the payment provider (default: 'Ecocash').
     * @param string $providerCode The code of the payment provider (default: 'EC').
     *
     * @return $this
     */
    public function setUpProvider(string $providerName = 'Ecocash', string $providerCode = 'EC'): self
    {
        $this->providerName = $providerName;
        $this->providerCode = $providerCode;

        return $this;
    }

    /**
     * Prepare payment payload for a transaction.
     *
     * @param float       $amount      The amount of the transaction.
     * @param string      $account     The account name or identifier.
     * @param string      $currency    The currency code (default: 'ZWL').
     * @param string|null $ref         The reference for the transaction (optional).
     * @param string      $description The description for the transaction (optional).
     * @param string      $cell        The cell number (optional).
     *
     * @return array The prepared payment payload.
     */
    public function preparePayload(
        float $amount,
        string $account,
        string $currency = 'ZWL',
        ?string $ref = null,
        string $description = "",
        string $cell = ""
    ): array {
        // If $cell is not provided, use $account
        $cell = ($cell === '') ? $account : $cell;

        // Generate reference if not provided
        $ref = ($ref === null) ? "V-" . (new Reference())->generate(8) : $ref;

        // Default description if not provided
        $description = ($description === '') ? 'Payment with ref:' . $ref : $description;

        // Construct and return payment payload
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
                "reference" => $ref
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
