<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\CartId;

class Item
{
    public function __construct(
        private int $quantity = 0,
        private null|ProductId $productId = null,
        private null|CartId $cartId = null
    ) {
        
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function productId(): ?ProductId
    {
        return $this->productId;
    }

    public function cartId(): ?CartId
    {
        return $this->cartId;
    }

    public function equals(Item $other): bool
    {
        return $this->quantity() === $other->quantity()
            && $this->productId() === $other->productId()
            && $this->cartId()->equals($other->cartId())
        ;
    }

    public function combinable(Item $other): bool
    {
        return $this->productId()->equals($other->productId())
            && $this->cartId()->equals($other->cartId())
        ;
    }

    public function combine(Item $other): Item
    {
        if (!$this->equals($other)) {
            throw new \InvalidArgumentException('Cannot combine different items');
        }

        return new Item(
            quantity: $this->quantity + $other->quantity(),
            productId: $this->productId,
            cartId: $this->cartId
        );
    }
}