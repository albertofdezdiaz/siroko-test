<?php

namespace App\Shopping\Application\Payment\Create;

use App\Shopping\Domain\Model\Cart\CartId;

class CreatePaymentRequest
{
    public function __construct(
        public CartId $cartId
    )
    {
        
    }
}