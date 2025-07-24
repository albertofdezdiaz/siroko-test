<?php

namespace App\Shopping\Domain\Model\Cart;

use DateTimeImmutable;
use App\Shopping\Domain\DomainEvent;
use App\Shared\Domain\Event\DomainEventId;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Payment\PaymentId;

final class CartProcessed extends DomainEvent
{
    protected static string $eventName = 'cartProcessed';

    public function __construct(
        private CartId $cartId, 
        private PaymentId $paymentId, 
        ?DomainEventId $id = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function paymentId(): PaymentId
    {
        return $this->paymentId;
    }
}