<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Shopping;

use Behat\Step\Given;
use Behat\Behat\Context\Context;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;

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
}
