<?php

namespace App\Shopping\Domain\Event\Payment;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\RegisterTrait;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shared\Domain\Event\DomainEventSubscriber;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Shopping\Domain\Model\Payment\PaymentPaid;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Cart\NonActiveCartException;
use App\Shopping\Domain\Model\Payment\PaymentNotFoundException;

class PaymentPaidSubscriber implements DomainEventSubscriber
{
    use RegisterTrait;

    public function __construct(
        private PaymentRepository $paymentRepository,
        private CartRepository $cartRepository
    ) {
        $this->register();
    }

    public function handle(DomainEvent $event)
    {
        if (!$this->isSubscribedTo($event)) {
            throw new \RuntimeException("Incorrect event!");
        }

        $this->handlePaymentPaid($event);
    }

    private function handlePaymentPaid(PaymentPaid $event)
    {
        $payment = $this->paymentRepository->find($event->paymentId());

        if (null === $payment) {
            throw new PaymentNotFoundException($event->paymentId());
        }

        $cart = $this->cartRepository->find($payment->cartId());

        if (null === $cart) {
            throw new CartNotFoundException($payment->cartId());
        }

        if (CartStatus::Active !== $cart->status()) {
            throw new NonActiveCartException($cart->id());
        }

        $cart->process($payment);

        $this->cartRepository->add($cart);
    }

    public function isSubscribedTo(DomainEvent $event)
    {
        return $event instanceof PaymentPaid;
    }
}
