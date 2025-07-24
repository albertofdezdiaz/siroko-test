<?php

namespace App\Shopping\Domain\Model\Order;

use DateTimeImmutable;
use App\Shopping\Domain\DomainEvent;
use App\Shared\Domain\Event\DomainEventId;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Order\OrderId;
use App\Shopping\Domain\Model\Payment\PaymentId;

final class OrderCreated extends DomainEvent
{
    protected static string $eventName = 'orderCreated';

    public function __construct(
        private OrderId $orderId, 
        private CartId $cartId,
        private PaymentId $paymentId,
        ?DomainEventId $id = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $occurredOn);
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
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