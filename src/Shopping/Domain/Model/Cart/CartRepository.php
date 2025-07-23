<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;

interface CartRepository
{
    public function add(Cart $cart): void;

    public function remove(Cart $cart): void;

    public function find(CartId $cartId): ?Cart;
}