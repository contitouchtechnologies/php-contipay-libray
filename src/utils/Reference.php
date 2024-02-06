<?php
namespace Contipay\Util;

class Reference
{
    function generate(int $len = 6, int $number = null)
    {
        switch ($len) {
            case 4:
                $input = random_int(0000, 9999);
                break;

            default:
                $input = random_int(000000, 999999);
                break;
        }
        $input = (is_null($number)) ? $input : $number;

        return str_pad($input, $len, '0', STR_PAD_LEFT);
    }
}