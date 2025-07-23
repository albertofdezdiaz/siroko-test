<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\CartId;

class ItemNotFoundException extends \RuntimeException
{
    public function __construct(CartId $cartId)
    {
        parent::__construct(
            sprintf("Item not found on cart %s", (string) $cartId), 
            404
        );
    }
}