<?php

namespace App\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\PaymentId;

class PaymentNotFoundException extends \RuntimeException
{
    public function __construct(PaymentId $cartId)
    {
        parent::__construct(
            sprintf("Payment %s not found", (string) $cartId), 
            404
        );
    }
}