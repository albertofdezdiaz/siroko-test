<?php

namespace App\Shopping\Application\Cart\RemoveItem;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;

class RemoveItemRequest
{
    public function __construct(
        public CartId $cartId,
        public ProductId $productId      
    ) {
        
    }
}