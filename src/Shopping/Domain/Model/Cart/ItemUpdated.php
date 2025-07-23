<?php

namespace App\Shopping\Domain\Model\Cart;

use DateTimeImmutable;
use App\Shopping\Domain\DomainEvent;
use App\Shared\Domain\Event\DomainEventId;
use App\Shopping\Domain\Model\Cart\CartId;

final class ItemUpdated extends DomainEvent
{
    protected static string $eventName = 'itemUpdated';

    public function __construct(
        private CartId $cartId, 
        private Item $item,
        ?DomainEventId $id = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function item(): Item
    {
        return $this->item;
    }
}