<?php
namespace Contipay\Core;

use GuzzleHttp\Client;

class Contipay
{
    /**
     * ContiPay token
     *
     * @var string
     */
    protected string $token;

    /**
     * ContiPay secret
     *
     * @var string
     */
    protected string $secret;
    /**
     * API url
     *
     * @var string
     */
    protected string $url;

    /**
     * Payment method. Either direct or redirect payment method
     *
     * @var string
     */
    protected string $paymentMethod = 'direct';

    /**
     * Acquire endpoint
     *
     * @var string
     */
    protected $acquireUrl = 'acquire/payment';

    /**
     * Uat url
     *
     * @var string
     */
    protected $uatURL = 'https://api2-test.contipay.co.zw';

    /**
     * Live url
     *
     * @var string
     */
    protected $liveURL = 'https://api-v2.contipay.co.zw';

    public function __construct(string $token, string $secret)
    {
        $this->token = $token;
        $this->secret = $secret;

    }

    /**
     * ContiPay environment mode
     *
     * @param string $mode
     * @return self
     */
    function setAppMode(string $mode = "DEV")
    {
        $this->url = ($mode == 'DEV') ? $this->uatURL : $this->liveURL;

        $this->initHttpClient();

        return $this;
    }

    /**
     * Update urls from the defaults
     *
     * @param string $devURL
     * @param string $liveURL
     * @return self
     */
    function updateURL(string $devURL, string $liveURL)
    {
        $this->uatURL = $devURL;
        $this->liveURL = $liveURL;

        return $this;
    }

    /**
     * Set payment method. By default it's direct
     *
     * @param string $method
     * @return self
     */

    /**
     * HTTP Client
     *
     */
    protected $client;

    function setPaymentMethod(string $method = 'direct')
    {
        $this->paymentMethod = ($method == 'direct') ? "POST" : "PUT";

        return $this;
    }


    /**
     * Process payment
     *
     * @param array $payload
     * 
     */
    function process(array $payload)
    {

        $client = $this->client;

        try {
            $response = $client->request($this->paymentMethod, "/{$this->acquireUrl}", [
                'auth' => [$this->token, $this->secret],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ],
                'json' => $payload
            ]);

            return $response->getBody()->getContents();
        } catch (\Throwable $th) {

            return json_encode(array('status' => 'Error', 'message' => $th->getMessage()));
        }

    }

    /**
     * Setup HTTP client
     *
     * @return self
     */
    function initHttpClient()
    {
        $client = new Client(['base_uri' => $this->url]);

        $this->client = $client;

        return $this;
    }


}