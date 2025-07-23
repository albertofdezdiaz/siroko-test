<?php

namespace App\Shopping\Infrastructure\Repository\Cart;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\CartRepository;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
class InMemoryCartRepository implements CartRepository
{
    public function __construct(private array $carts = [])
    {

    }

    public function add(Cart $cart): void
    {
        if (isset($this->carts[(string) $cart->id()])) {
            return ;
        }

        $this->carts[(string) $cart->id()] = $cart;
    }

    public function remove(Cart $cart): void
    {
        if (!isset($this->carts[(string) $cart->id()])) {
            return ;
        }

        unset($this->carts[(string) $cart->id()]);
    }

    public function find(CartId $cartId): ?Cart
    {
        return isset($this->carts[(string) $cartId])
            ? $this->carts[(string) $cartId]
            : null
        ;
    }
}