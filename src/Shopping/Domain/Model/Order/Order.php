<?php

namespace App\Shopping\Domain\Model\Order;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Payment\PaymentId;

class Order
{
    use AggregateRoot;

    private array $events = [];

    public function __construct(
        private OrderId $id, 
        private ?CartId $cartId = null,
        private ?PaymentId $paymentId = null,
        private ?\DateTimeImmutable $createdAt = null
    ) {
        
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function cartId(): ?CartId
    {
        return $this->cartId;
    }

    public function paymentId(): ?PaymentId
    {
        return $this->paymentId;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function create(CartId $cartId, PaymentId $paymentId)
    {
        $this->recordApplyAndPublish(
            new OrderCreated(
                orderId: $this->id(),
                paymentId: $paymentId,
                cartId: $cartId,
            )
        );
    }

    public function applyOrderCreated(OrderCreated $event)
    {
        $this->paymentId = $event->paymentId();
        $this->cartId = $event->cartId();
        $this->createdAt = $event->occurredOn();
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }
}