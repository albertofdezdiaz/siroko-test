<?php

namespace App\Shopping\Application\Cart\Create;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\CartRepository;

class CreateCartService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(CreateCartRequest $request): CreateCartResponse
    {
        $id = CartId::generate();

        $cart = new Cart($id);
        $cart->create();

        $this->cartRepository->add($cart);

        return new CreateCartResponse(
            cartId: $cart->id(), 
            status: $cart->status()
        );
    }
}