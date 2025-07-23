<?php

namespace App\Shopping\Application\Cart\View;

use App\Shopping\Domain\Model\Cart\Cart;

class ViewCartResponse
{
    public function __construct(
        public Cart $cart
    ) {
        
    }
}