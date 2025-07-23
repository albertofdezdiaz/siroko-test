<?php

namespace App\Shopping\Application\Cart\UpdateItem;

use App\Shopping\Domain\Model\Cart\Item;

class UpdateItemResponse
{
    public function __construct(
        public Item $item
    ) {
        
    }
}