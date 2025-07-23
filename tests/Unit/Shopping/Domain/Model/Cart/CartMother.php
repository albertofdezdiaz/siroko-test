<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\CartStatus;

class CartMother
{
    public static function fromStatus(string $status)
    {
        return self::from(
            cartId: CartId::generate(),
            status: CartStatus::tryFrom($status),
        );
    }

    public static function fromStatusAndId(string $status, string $cartId)
    {
        return self::from(
            cartId: new CartId($cartId),
            status: CartStatus::tryFrom($status),
        );
    }

    public static function from(CartId $cartId, CartStatus $status)
    {
        $cart = new Cart(
            id: $cartId,
            status: $status,
            createdAt: new \DateTimeImmutable('now')
        );

        return $cart;
    }

    public static function random()
    {
        return self::from(
            cartId: CartId::generate(),
            status: rand(0, 1) ? CartStatus::Active : CartStatus::Processed
        );
    }
}