<?php

namespace App\Shopping\Domain\Model\Payment;

use DateTimeImmutable;
use App\Shopping\Domain\DomainEvent;
use App\Shared\Domain\Event\DomainEventId;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Payment\PaymentId;

final class PaymentCreated extends DomainEvent
{
    protected static string $eventName = 'paymentCreated';

    public function __construct(
        private PaymentId $paymentId, 
        private CartId $cartId, 
        ?DomainEventId $id = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public function paymentId(): PaymentId
    {
        return $this->paymentId;
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }
}