<?php

namespace App\Shopping\Application\Payment\Pay;

use App\Shopping\Domain\Model\Payment\PaymentId;

class PayPaymentRequest
{
    public function __construct(
        public PaymentId $paymentId
    )
    {
        
    }
}