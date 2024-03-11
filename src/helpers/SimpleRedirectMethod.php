<?php

namespace Contipay\Helpers;

use Contipay\Util\Reference;

class SimpleRedirectMethod
{
    protected string $successUrl;
    protected string $cancelUrl;
    protected string $webhookUrl;
    protected int $merchantId;

    /**
     * Constructor for initializing class properties.
     *
     * @param int    $merchantId  The merchant ID.
     * @param string $webhookUrl  The URL for webhook notifications.
     * @param string $successUrl  The URL to redirect to upon successful transaction.
     * @param string $cancelUrl   The URL to redirect to upon canceled transaction.
     */
    public function __construct(int $merchantId, string $webhookUrl, string $successUrl, string $cancelUrl)
    {
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->merchantId = $merchantId;
    }

    /**
     * Prepare payment payload for transaction.
     *
     * @param float       $amount      The amount of the transaction.
     * @param string      $account     The account name or identifier.
     * @param string      $currency    The currency code (default: 'USD').
     * @param string|null $ref         The reference for the transaction (optional).
     * @param string      $description The description for the transaction (optional).
     * @param string      $cell        The cell number (optional).
     * @param bool        $isCod       Indicates if the transaction is Cash on Delivery (optional).
     * @param bool        $isCoc       Indicates if the transaction is Cash on Collection (optional).
     *
     * @return array The prepared payment payload.
     */
    public function preparePayload(
        float $amount,
        string $account,
        string $currency = 'USD',
        ?string $ref = null,
        string $description = "",
        string $cell = "",
        bool $isCod = false,
        bool $isCoc = false
    ): array {
        // If $cell is not provided, use $account
        $cell = ($cell === '') ? $account : $cell;

        // Generate reference if not provided
        $ref = ($ref === null) ? "V-" . (new Reference())->generate(8) : $ref;

        // Default description if not provided
        $description = ($description === '') ? 'Payment with ref:' . $ref : $description;

        // Construct and return payment payload
        return [
            "reference" => $ref,
            'cod' => $isCod,
            'coc' => $isCoc,
            "description" => $description,
            "amount" => $amount,
            'customer' => [
                'firstName' => $account,
                'surname' => $account,
                'middleName' => '-',
                "nationalId" => '-',
                'email' => "$account@contipay.co.zw",
                'cell' => $cell,
                'countryCode' => 'zw',
            ],
            "currencyCode" => $currency,
            "merchantId" => $this->merchantId,
            "webhookUrl" => $this->webhookUrl,
            'successUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl,
        ];
    }
}
