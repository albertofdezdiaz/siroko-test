<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;

class ItemMother
{
    public static function from(CartId $cartId, ProductId $productId, int $quantity): Item
    {
        $item = new Item(
            cartId: $cartId,
            productId: $productId,
            quantity: $quantity
        );

        return $item;
    }

    public static function random()
    {
        return self::from(
            cartId: CartId::generate(),
            productId: ProductId::generate(),
            quantity: rand(1, 10),
        );
    }
}