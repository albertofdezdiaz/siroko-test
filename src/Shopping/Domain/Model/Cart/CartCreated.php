<?php

namespace App\Shopping\Domain\Model\Cart;

use DateTimeImmutable;
use App\Shopping\Domain\DomainEvent;
use App\Shared\Domain\Event\DomainEventId;
use App\Shopping\Domain\Model\Cart\CartId;

final class CartCreated extends DomainEvent
{
    protected static string $eventName = 'cartCreated';

    public function __construct(
        private CartId $cartId, 
        ?DomainEventId $id = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }
}