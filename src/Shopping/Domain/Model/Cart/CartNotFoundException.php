<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\CartId;

class CartNotFoundException extends \RuntimeException
{
    public function __construct(CartId $cartId)
    {
        parent::__construct(
            sprintf("Cart %s not found", (string) $cartId), 
            404
        );
    }
}