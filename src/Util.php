<?php

namespace ContiTouch\Contipay;

class Util
{
    /**
     * Base64 Encode
     *
     * @param $val
     * @return string
     */
    public function encode($val)
    {
        return base64_encode($val);
    }

    /**
     * Base64 Decode
     *
     * @param $val
     * @return string
     */
    public function decode($val)
    {
        return base64_decode($val);
    }

    /**
     * Sanitize auth
     *
     * @param array $val
     * @param integer $index
     * @return string
     */
    public function sanitizeAuth(array $val, int $index = 0)
    {
        $sanitized_auth = explode(":", base64_encode($val[$index]));

        return $sanitized_auth;
    }

    /**
     * create basic auth
     *
     * @param array $arr
     * @return string
     */
    public function basicAuth(array $arr)
    {
        $username = $this->sanitizeAuth($arr, 0);
        $password = $this->sanitizeAuth($arr, 1);

        $colon = $this->encode(':');

        $num1 = $this->decode($username[0]);
        $num2 = $this->decode($password[0]);
        $num3 = $this->decode($colon);

        $auth = $num1 . $num3 . $num2;

        $sanitized_auth = $this->encode($auth);

        return $sanitized_auth;
    }
}
