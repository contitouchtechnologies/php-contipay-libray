<?php

namespace Contipay\Helpers\Checksum;

class ChecksumGenerator
{
    /**
     * Generate checksum using SHA-256 hashing algorithm.
     *
     * @param mixed $data Data to be hashed.
     * @return string Generated checksum.
     */
    public static function generateChecksum($data): string
    {
        // Convert data to a string if it's not already a string
        $dataString = is_string($data) ? $data : json_encode($data);

        // Generate checksum using SHA-256 algorithm
        $checksum = hash('sha256', $dataString);

        return $checksum;
    }
}
