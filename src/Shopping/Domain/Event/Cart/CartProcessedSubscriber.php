<?php

namespace App\Shopping\Domain\Event\Cart;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\RegisterTrait;
use App\Shopping\Domain\Model\Order\Order;
use App\Shopping\Domain\Model\Order\OrderId;
use App\Shopping\Domain\Model\Cart\CartProcessed;
use App\Shared\Domain\Event\DomainEventSubscriber;
use App\Shopping\Domain\Model\Order\OrderRepository;

class CartProcessedSubscriber implements DomainEventSubscriber
{
    use RegisterTrait;

    public function __construct(
        private OrderRepository $repository
    ) {
        $this->register();
    }

    public function handle(DomainEvent $event)
    {
        if (!$this->isSubscribedTo($event)) {
            throw new \RuntimeException("Incorrect event!");
        }

        $this->handleCartProcessed($event);
    }

    private function handleCartProcessed(CartProcessed $event)
    {
        $order = new Order(
            id: OrderId::generate()
        );
        $order->create($event->cartId(), $event->paymentId());

        $this->repository->add($order);
    }

    public function isSubscribedTo(DomainEvent $event)
    {
        return $event instanceof CartProcessed;
    }
}
