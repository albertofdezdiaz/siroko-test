<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\CartId;

class NonActiveCartException extends \RuntimeException
{
    public function __construct(CartId $cartId)
    {
        parent::__construct(
            sprintf("The content of %s can't be modified", (string) $cartId), 
            423
        );
    }
}