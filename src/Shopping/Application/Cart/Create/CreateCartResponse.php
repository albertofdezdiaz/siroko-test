<?php

namespace App\Shopping\Application\Cart\Create;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\CartStatus;

class CreateCartResponse
{
    public function __construct(public CartId $cartId, public CartStatus $status)
    {
        
    }
}