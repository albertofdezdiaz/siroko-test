<?php

namespace App\Shopping\Application\Cart\AddItem;

use App\Shopping\Domain\Model\Cart\Item;

class AddItemResponse
{
    public function __construct(
        public Item $item
    ) {
        
    }
}