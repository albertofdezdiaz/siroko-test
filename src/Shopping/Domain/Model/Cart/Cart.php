<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Item;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shopping\Domain\Model\Cart\ItemAdded;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Cart\CartProcessed;
use App\Shopping\Domain\Model\Cart\ItemCollection;
use App\Shopping\Domain\Model\Cart\ItemNotFoundException;
use App\Shopping\Domain\Model\Cart\NonActiveCartException;

class Cart
{
    use AggregateRoot;

    private array $events = [];

    private ItemCollection $items;

    public function __construct(
        private CartId $id, 
        private ?CartStatus $status = null,
        private ?\DateTimeImmutable $createdAt = null
    ) {
        $this->items = new ItemCollection;
    }

    public function id(): CartId
    {
        return $this->id;
    }

    public function status(): CartStatus
    {
        return $this->status;
    }

    public function items(): ItemCollection
    {
        return $this->items;
    }
    
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function create()
    {
        $this->recordApplyAndPublish(
            new CartCreated(
                cartId: $this->id(),
            )
        );
    }

    public function process(Payment $payment)
    {
        $this->recordApplyAndPublish(
            new CartProcessed(
                cartId: $this->id(),
                paymentId: $payment->id()
            )
        );
    }

    public function addItem(Item $item)
    {
        if ($this->status() !== CartStatus::Active) {
            throw new NonActiveCartException($this->id());
        }

        $this->recordApplyAndPublish(
            new ItemAdded(
                cartId: $this->id(),
                item: $item
            )
        );
    }

    public function updateItem(ProductId $productId, int $quantity)
    {
        if ($this->status() !== CartStatus::Active) {
            throw new NonActiveCartException($this->id());
        }

        if ($quantity == 0) {
            return $this->removeItem($productId);
        }

        $item = new Item(
            quantity: $quantity,
            productId: $productId,
            cartId: $this->id()
        );

        $itemFound = $this->items()->findCombinable($item);

        if (null === $itemFound) {
            throw new ItemNotFoundException($this->id());
        }

        $this->recordApplyAndPublish(
            new ItemUpdated(
                cartId: $this->id(),
                item: $item
            )
        );
    }

    public function removeItem(ProductId $productId)
    {
        if ($this->status() !== CartStatus::Active) {
            throw new NonActiveCartException($this->id());
        }

        $item = new Item(
            quantity: 0,
            productId: $productId,
            cartId: $this->id()
        );

        $itemFound = $this->items()->findCombinable($item);

        if (null === $itemFound) {
            throw new ItemNotFoundException($this->id());
        }

        $this->recordApplyAndPublish(
            new ItemRemoved(
                cartId: $this->id(),
                item: $itemFound
            )
        );
    }

    public function applyCartCreated(CartCreated $event)
    {
        $this->status = CartStatus::Active;
        $this->createdAt = $event->occurredOn();
    }

    public function applyCartProcessed(CartProcessed $event)
    {
        $this->status = CartStatus::Processed;
    }

    public function applyItemAdded(ItemAdded $event)
    {
        $this->items->add($event->item());
    }

    public function applyItemRemoved(ItemRemoved $event)
    {
        $this->items->remove($event->item());
    }
    
    public function applyItemUpdated(ItemUpdated $event)
    {
        $itemFound = $this->items->findCombinable($event->item());

        $this->items->remove($itemFound);

        $this->items->add($event->item());
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }
}