<?php

namespace App\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\Item;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shopping\Domain\Model\Cart\ItemAdded;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\ItemCollection;
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

    public function applyCartCreated(Cartcreated $event)
    {
        $this->status = CartStatus::Active;
        $this->createdAt = $event->occurredOn();
    }

    public function applyItemAdded(ItemAdded $event)
    {
        $this->items->add($event->item());
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }
}