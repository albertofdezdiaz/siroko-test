<?php

namespace App\Shopping\Application\Cart\RemoveItem;

use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;

class RemoveItemService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(RemoveItemRequest $request): RemoveItemResponse
    {
        $cart = $this->cartRepository->find($request->cartId);

        if (null === $cart) {
            throw new CartNotFoundException($request->cartId);
        }

        $cart->removeItem(
            $request->productId
        );

        $this->cartRepository->add($cart);

        return new RemoveItemResponse(
            item: new Item(
                quantity: 0,
                productId: $request->productId,
                cartId: $request->cartId
            )
        );
    }
}