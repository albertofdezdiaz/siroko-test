<?php

namespace App\Shopping\Application\Cart\AddItem;

use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartRepository;

class AddItemService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(AddItemRequest $request): AddItemResponse
    {
        $cart = $this->cartRepository->find($request->cartId);

        if (null === $cart) {
            throw new CartNotFoundException($request->cartId);
        }

        $item = new Item(
            quantity: $request->quantity,
            productId: $request->productId,
            cartId: $request->cartId
        );

        $cart->addItem(
            $item
        );

        $this->cartRepository->add($cart);

        return new AddItemResponse(
            item: $cart->items()->findCombinable($item)
        );
    }
}