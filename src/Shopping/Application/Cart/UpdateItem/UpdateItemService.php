<?php

namespace App\Shopping\Application\Cart\UpdateItem;

use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartRepository;

class UpdateItemService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(UpdateItemRequest $request): UpdateItemResponse
    {
        $cart = $this->cartRepository->find($request->cartId);

        if (null === $cart) {
            throw new CartNotFoundException($request->cartId);
        }

        $cart->updateItem(
            $request->productId, $request->quantity
        );

        $this->cartRepository->add($cart);

        return new UpdateItemResponse(
            item: new Item(
                productId: $request->productId,
                cartId: $cart->id(),
                quantity: $request->quantity
            )
        );
    }
}