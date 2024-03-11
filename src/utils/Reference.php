<?php

namespace Contipay\Util;

class Reference
{
    /**
     * Generate a reference number with the specified length.
     *
     * @param int $len The length of the reference number (default: 6).
     * @param int|null $number An optional number to use in the reference (default: null).
     * @return string The generated reference number.
     */
    public function generate(int $len = 6, ?int $number = null): string
    {
        $input = $number ?? random_int(0, 10 ** $len - 1);

        return str_pad((string) $input, $len, '0', STR_PAD_LEFT);
    }
}
