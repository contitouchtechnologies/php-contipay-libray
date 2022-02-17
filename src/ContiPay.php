<?php

namespace ContiTouch\Contipay;

use ContiTouch\Contipay\Http;
use ContiTouch\Contipay\Util;

class ContiPay
{
    protected $mode;
    protected $acquire =  '/acquire/payment';

    public function __construct()
    {
        $this->mode = getenv('CONTIPAY_MODE');
    }

    /**
     * Process ContiPay Payment
     *
     * @param array $payload
     * @param boolean $isRedirect
     * 
     */
    public function processPayment(array $payload, bool $isRedirect = false)
    {
        $payload = json_encode($payload);

        $url = $this->getPath();
        $auth = $this->getBasicAuth();

        $http = new Http();

        $method = ($isRedirect) ? "PUT" : "POST";

        $http->send($url, $payload, $method, $auth);

        return $http->getResponse();
    }

    /**
     * Contipay enviroment url
     *
     * @return string
     */
    public function environment()
    {
        $urls = array(
            "DEV" => getenv('CP_DEV_URL'),
            "LIVE" => getenv('CP_LIVE_URL')
        );

        return $urls[$this->mode];
    }

    /**
     * Get enviroment path
     *
     * @return string
     */
    public function getPath()
    {
        $environment_mode = ($this->mode == "DEV") ? 'TRUE' : 'FALSE';

        $url = ("FALSE" == $environment_mode)
            ? $this->environment($this->mode)
            : $this->environment($this->mode);

        return $url . $this->acquire;
    }

    public function getBasicAuth()
    {
        $util = new Util();

        return $util->basicAuth([
            getenv('CP_TOKEN'),
            getenv('CP_SECRET')
        ]);
    }
}
