<?php

namespace App\Shopping\Application\Cart\AddItem;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;

class AddItemRequest
{
    public function __construct(
        public CartId $cartId,
        public ProductId $productId,
        public int $quantity        
    ) {
        
    }
}