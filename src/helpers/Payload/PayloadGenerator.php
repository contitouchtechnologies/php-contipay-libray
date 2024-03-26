<?php
namespace Contipay\Helpers\Payload;

use Contipay\Util\Reference;

class PayloadGenerator
{
    private const DEFAULT_COUNTRY_CODE = 'ZW';
    private const DEFAULT_CURRENCY = 'ZWL';
    private const DEFAULT_MIDDLE_NAME = '-';
    private const DEFAULT_NATIONAL_ID = '-';
    private const DEFAULT_ACCOUNT_NAME = '-';
    private const DEFAULT_ACCOUNT_EXPIRY = '-';

    protected int $merchantCode;
    protected string $webhookUrl;
    protected string $successUrl;
    protected string $cancelUrl;
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

    public function __construct(int $merchantCode, string $webhookUrl, string $successUrl = "", string $cancelUrl = "")
    {
        $this->merchantCode = $merchantCode;
        $this->webhookUrl = $webhookUrl;
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
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
        string $countryCode = self::DEFAULT_COUNTRY_CODE,
        string $email = "",
        string $middleName = self::DEFAULT_MIDDLE_NAME,
        string $nationalId = self::DEFAULT_NATIONAL_ID
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
        string $currency = self::DEFAULT_CURRENCY,
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
        string $accountName = self::DEFAULT_ACCOUNT_NAME,
        string $accountExpiry = self::DEFAULT_ACCOUNT_EXPIRY,
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
     * Prepare payment direct payload for a transaction.
     *
     * @return array The prepared payment payload.
     */
    public function directPayload(): array
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

    /**
     * Prepare payment redirect payload for a transaction.
     *
     * @param bool $isCoc  Indicates if the transaction is Cash on Collection (default: false).
     * @param bool $isCod  Indicates if the transaction is Cash on Delivery (default: false
     *
     * @return array The prepared payment payload.
     */
    public function redirectPayload(bool $isCoc = false, bool $isCod = false): array
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

    /**
     * Prepare payment simple direct payload for a transaction.
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
    public function simpleDirectPayload(
        float $amount,
        string $account,
        string $currency = self::DEFAULT_CURRENCY,
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
                "nationalId" => self::DEFAULT_NATIONAL_ID,
                "firstName" => $account,
                "middleName" => self::DEFAULT_MIDDLE_NAME,
                "surname" => '-',
                "email" => "$account@contipay.co.zw",
                "cell" => $cell,
                "countryCode" => self::DEFAULT_COUNTRY_CODE
            ],
            "transaction" => [
                "providerCode" => $this->providerCode,
                "providerName" => $this->providerName,
                "amount" => $amount,
                "currencyCode" => $currency,
                "description" => $description,
                "webhookUrl" => $this->webhookUrl,
                "merchantId" => $this->merchantCode,
                "reference" => $ref
            ],
            "accountDetails" => [
                "accountNumber" => $account,
                "accountName" => self::DEFAULT_ACCOUNT_NAME,
                "accountExtra" => [
                    "smsNumber" => $cell,
                    "expiry" => self::DEFAULT_ACCOUNT_EXPIRY,
                    "cvv" => "003"
                ]
            ]
        ];
    }

    /**
     * Prepare payment simple redirect payload for transaction.
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
    public function simpleRedirectPayload(
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
                'middleName' => self::DEFAULT_MIDDLE_NAME,
                "nationalId" => self::DEFAULT_NATIONAL_ID,

                'email' => "$account@contipay.co.zw",
                'cell' => $cell,
                'countryCode' => self::DEFAULT_COUNTRY_CODE,
            ],
            "currencyCode" => $currency,
            "merchantId" => $this->merchantCode,
            "webhookUrl" => $this->webhookUrl,
            'successUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl
        ];
    }
}
