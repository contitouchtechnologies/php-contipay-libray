<?php

namespace ContiTouch\Contipay;

/**
 * custom cURL wrapper
 */
class Http
{

    protected $response;

    public function send($path = null, $payload = null, $method = null, $auth = '')
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$path",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                "Authorization: Basic $auth",
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->setResponse($response);
    }

    /**
     * Get the value of response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the value of response
     *
     * @return  self
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }
}
