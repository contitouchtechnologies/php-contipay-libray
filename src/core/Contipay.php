<?php

namespace Contipay\Core;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nigel\Utils\Core\Checksums\ContipayChecksum;

class Contipay
{
    protected string $token;
    protected string $secret;
    protected string $url;
    protected string $paymentMethod = 'direct';
    protected string $acquireUrl = 'acquire/payment';
    protected string $disburseUrl = 'disburse/payment';
    protected string $uatURL = 'https://api2-test.contipay.co.zw';
    protected string $liveURL = 'https://api-v2.contipay.co.zw';
    protected ?Client $client = null;
    protected string $checksum;

    public function __construct(string $token, string $secret)
    {
        $this->token = $token;
        $this->secret = $secret;
    }

    /**
     * Set ContiPay environment mode.
     *
     * @param string $mode
     * @return self
     */
    public function setAppMode(string $mode = "DEV"): self
    {
        $this->url = ($mode == 'DEV') ? $this->uatURL : $this->liveURL;
        $this->initHttpClient();
        return $this;
    }

    /**
     * Update URLs from the defaults.
     *
     * @param string $devURL
     * @param string $liveURL
     * @return self
     */
    public function updateURL(string $devURL, string $liveURL): self
    {
        $this->uatURL = $devURL;
        $this->liveURL = $liveURL;
        return $this;
    }

    /**
     * Set payment method. By default it's direct.
     *
     * @param string $method
     * @return self
     */
    public function setPaymentMethod(string $method = 'direct'): self
    {
        $this->paymentMethod = ($method == 'direct') ? "POST" : "PUT";
        return $this;
    }

    /**
     * Process payment.
     *
     * @param array $payload
     * @return string JSON response
     */
    public function process(array $payload): string
    {
        try {
            $response = $this->client->request($this->paymentMethod, "/{$this->acquireUrl}", [
                'auth' => [$this->token, $this->secret],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ],
                'json' => $payload
            ]);

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return json_encode(['status' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Disburse payment.
     *
     * @param array $payload
     * @param string $privateKey
     * @return string JSON response
     */
    function disburse(array $payload, string $privateKey)
    {

        try {

            $this->generateChecksum($payload, $privateKey);

            $response = $this->client->request($this->paymentMethod, "/{$this->disburseUrl}", [
                'auth' => [$this->token, $this->secret],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                    'checksum' => $this->checksum
                ],
                'json' => $payload
            ]);

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return json_encode(['status' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate checksum for the given payload using the provided private key.
     *
     * @param array  $payload    The payload containing transaction and account details.
     * @param string $privateKey The private key used for generating the checksum.
     *
     * @return self Returns an instance of this class with the generated checksum.
     * @throws Exception If the private key retrieval fails.
     */
    function generateChecksum(array $payload, string $privateKey): self
    {
        $reference = $payload['transaction']['reference'];
        $merchantId = $payload['transaction']['merchantId'];
        $accountNumber = $payload['accountDetails']['accountNumber'];
        $amount = $payload['transaction']['amount'];


        $dataToEncrypt = $this->token . $reference . $merchantId . $accountNumber . $amount;

        $privateKeyResource = openssl_get_privatekey($privateKey, "");
        if (!$privateKeyResource) {
            throw new Exception("Failed to retrieve private key");
        }

        $this->checksum = (new ContipayChecksum())->generateChecksum($dataToEncrypt, true, $privateKeyResource);

        return $this;
    }


    /**
     * Setup HTTP client.
     *
     * @return void
     */
    protected function initHttpClient(): void
    {
        $this->client = new Client(['base_uri' => $this->url]);
    }
}
