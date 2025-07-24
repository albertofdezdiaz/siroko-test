<?php

namespace App\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\PaymentId;

class PaymentAlreadyPaidException extends \RuntimeException
{
    public function __construct(PaymentId $paymentId)
    {
        parent::__construct(
            sprintf("Payment %s already paid", (string) $paymentId), 
            404
        );
    }
}