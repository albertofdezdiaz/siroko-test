<?php

namespace App\Shopping\Application\Cart\View;

use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Cart\CartRepository;

class ViewCartService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(ViewCartRequest $request): ViewCartResponse
    {
        $cart = $this->cartRepository->find($request->cartId);

        if (null === $cart) {
            throw new CartNotFoundException($request->cartId);
        }

        return new ViewCartResponse(
            cart: $cart
        );
    }
}