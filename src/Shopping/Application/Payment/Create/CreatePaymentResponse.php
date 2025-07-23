<?php

namespace App\Shopping\Application\Payment\Create;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Payment\PaymentStatus;

class CreatePaymentResponse
{
    public function __construct(public CartId $cartId, public PaymentId $paymentId, public PaymentStatus $status)
    {
        
    }
}