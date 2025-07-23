<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Shopping;

use Behat\Step\Given;
use Behat\Behat\Context\Context;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Tests\Unit\Shopping\Domain\Model\Cart\ItemMother;

final class ShoppingContext implements Context
{
    public function __construct(
        private CartRepository $cartRepository
    ) {
    }

    #[Given('an active cart with id :cartId exists')]
    public function anActiveCartWithIdExists($cartId): void
    {
        $cart = CartMother::fromStatusAndId(
            status: CartStatus::Active->value,
            cartId: $cartId
        );

        $this->cartRepository->add($cart);
    }

    #[Given('an item :productId of quantity :quantity is added to :cartId')]
    public function anItemOfQuantityIsAddedTo($productId, $quantity, $cartId): void
    {
        $item = ItemMother::from(
            cartId: new CartId($cartId),
            productId: new ProductId($productId),
            quantity: (int) $quantity
        );

        $cart = $this->cartRepository->find($item->cartId());

        $cart->items()->add($item);

        $this->cartRepository->add($cart);
    }
}
