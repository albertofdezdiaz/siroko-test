<?php

namespace App\Shopping\Application\Cart\RemoveItem;

use App\Shopping\Domain\Model\Cart\Item;

class RemoveItemResponse
{
    public function __construct(
        public Item $item
    ) {
        
    }
}