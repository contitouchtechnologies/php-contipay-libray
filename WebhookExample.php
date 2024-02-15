<?php

class WebhookExample
{
    /**
     * Transaction is awaiting Confirmation or further Processing
     *
     * @var int
     */
    const PENDING = 0;

    /**
     * Transaction has been paid Sucesfully
     *
     * @var int
     */
    const PAID = 1;

    /**
     * Transaction has been Refunded back to Client from merchant
     *
     * @var int
     */
    const REFUNDED = 2;

    /**
     * Transaction Has failed with to process due to an error
     *
     * @var int
     */
    const ERROR = 3;

    /**
     * Transaction was declined
     *
     * @var int
     */
    const DECLINED = 4;

    /**
     * Transaction has been Confirmed
     *
     * @var int
     */
    const CONFIRMED = 5;

    /**
     * Transaction has been Queued
     *
     * @var int
     */
    const QUEUED = 6;

    /**
     * Transaction has been Approved
     *
     * @var int
     */
    const APPROVED = 7;

    /**
     * Transaction has been posted to external party fo further processing
     *
     * @var int
     */
    const SUBMITTED = 8;

    public static function hook($response, $transaction_id)
    {
        if ($response->statusCode != 3) {
            if (in_array($response->statusCode, [self::QUEUED, self::APPROVED, self::PENDING])) {
                // handle when transaction is pending
            } else if ($response->statusCode == self::CONFIRMED) {
                // handle when transaction is confirmed(optional)
            } else if ($response->statusCode == self::PAID) {
                // handle when transaction is paid
            } else if ($response->statusCode == 9) {
                // handle when transaction is not paid and errors occurred
            } else {
                // handle when transaction is not paid and errors occurred
            }
        } else {
            // handle when transaction is not paid and errors occurred
        }
    }
}
