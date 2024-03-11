<?php

namespace Contipay\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Contipay
{
    protected string $token;
    protected string $secret;
    protected string $url;
    protected string $paymentMethod = 'direct';
    protected string $acquireUrl = 'acquire/payment';
    protected string $uatURL = 'https://api2-test.contipay.co.zw';
    protected string $liveURL = 'https://api-v2.contipay.co.zw';
    protected ?Client $client = null;

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
     * Setup HTTP client.
     *
     * @return void
     */
    protected function initHttpClient(): void
    {
        $this->client = new Client(['base_uri' => $this->url]);
    }
}
