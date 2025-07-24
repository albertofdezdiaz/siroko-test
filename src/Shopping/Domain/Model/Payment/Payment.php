<?php

namespace App\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Cart\Cart;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Shopping\Domain\Model\Cart\NonActiveCartException;

class Payment
{
    use AggregateRoot;

    private array $events = [];

    public function __construct(
        private PaymentId $id, 
        private ?CartId $cartId = null,
        private ?PaymentStatus $status = null,
        private ?\DateTimeImmutable $createdAt = null
    ) {
        
    }

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function cartId(): ?CartId
    {
        return $this->cartId;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }
    
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function create(Cart $cart)
    {
        if ($cart->status() !== CartStatus::Active) {
            throw new NonActiveCartException($cart->id());
        }

        $this->recordApplyAndPublish(
            new PaymentCreated(
                paymentId: $this->id(),
                cartId: $cart->id(),
            )
        );
    }

    public function pay()
    {
        if ($this->status() === PaymentStatus::Paid) {
            throw new PaymentAlreadyPaidException($this->id());
        }

        $this->recordApplyAndPublish(
            new PaymentPaid(
                paymentId: $this->id()
            )
        );
    }

    public function applyPaymentCreated(PaymentCreated $event)
    {
        $this->cartId = $event->cartId();
        $this->status = PaymentStatus::Pending;
        $this->createdAt = $event->occurredOn();
    }

    public function applyPaymentPaid(PaymentPaid $event)
    {
        $this->status = PaymentStatus::Paid;
    }

    public function __toString(): string
    {
        return (string) $this->id();
    }
}