<?php

namespace App\Shopping\Application\Cart\UpdateItem;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;

class UpdateItemRequest
{
    public function __construct(
        public CartId $cartId,
        public ProductId $productId,
        public int $quantity        
    ) {
        
    }
}