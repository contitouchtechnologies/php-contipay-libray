<?php

class WebhookExample
{
    /**
     * Transaction status: Pending - awaiting confirmation or further processing.
     */
    const STATUS_PENDING = 0;

    /**
     * Transaction status: Paid - successfully paid.
     */
    const STATUS_PAID = 1;

    /**
     * Transaction status: Refunded - refunded back to the client from the merchant.
     */
    const STATUS_REFUNDED = 2;

    /**
     * Transaction status: Error - failed to process due to an error.
     */
    const STATUS_ERROR = 3;

    /**
     * Transaction status: Declined - transaction was declined.
     */
    const STATUS_DECLINED = 4;

    /**
     * Transaction status: Confirmed - transaction has been confirmed.
     */
    const STATUS_CONFIRMED = 5;

    /**
     * Transaction status: Queued - transaction has been queued.
     */
    const STATUS_QUEUED = 6;

    /**
     * Transaction status: Approved - transaction has been approved.
     */
    const STATUS_APPROVED = 7;

    /**
     * Transaction status: Submitted - transaction has been posted to an external party for further processing.
     */
    const STATUS_SUBMITTED = 8;

    /**
     * Handle webhook response based on transaction status.
     *
     * @param object $response The webhook response object.
     * @param int $transaction_id The ID of the transaction.
     * @return void
     */
    public static function handleWebhookResponse(object $response, int $transaction_id): void
    {
        switch ($response->statusCode) {
            case self::STATUS_QUEUED:
            case self::STATUS_APPROVED:
            case self::STATUS_PENDING:
                self::handlePendingTransaction();
                break;
            case self::STATUS_CONFIRMED:
                self::handleConfirmedTransaction();
                break;
            case self::STATUS_PAID:
                self::handlePaidTransaction();
                break;
            case 9:
                self::handleUnpaidTransactionWithError();
                break;
            default:
                self::handleUnpaidTransactionWithOtherErrors();
                break;
        }
    }

    /**
     * Handle pending transaction.
     *
     * @return void
     */
    private static function handlePendingTransaction(): void
    {
        // Handle when transaction is pending.
    }

    /**
     * Handle confirmed transaction.
     *
     * @return void
     */
    private static function handleConfirmedTransaction(): void
    {
        // Handle when transaction is confirmed (optional).
    }

    /**
     * Handle paid transaction.
     *
     * @return void
     */
    private static function handlePaidTransaction(): void
    {
        // Handle when transaction is paid.
    }

    /**
     * Handle unpaid transaction with error.
     *
     * @return void
     */
    private static function handleUnpaidTransactionWithError(): void
    {
        // Handle when transaction is not paid and errors occurred.
    }

    /**
     * Handle unpaid transaction with other errors.
     *
     * @return void
     */
    private static function handleUnpaidTransactionWithOtherErrors(): void
    {
        // Handle when transaction is not paid and errors occurred.
    }
}
