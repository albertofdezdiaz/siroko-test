<?php

namespace App\Shopping\Application\Cart\View;

use App\Shopping\Domain\Model\Cart\CartId;

class ViewCartRequest
{
    public function __construct(
        public CartId $cartId       
    ) {
        
    }
}