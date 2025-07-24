<?php

namespace App\Shopping\Infrastructure\Repository\Order;

use App\Shopping\Domain\Model\Order\Order;
use App\Shopping\Domain\Model\Order\OrderId;
use App\Shopping\Domain\Model\Order\OrderRepository;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
class InMemoryOrderRepository implements OrderRepository
{
    public function __construct(private array $orders = [])
    {

    }

    public function add(Order $order): void
    {
        if (isset($this->orders[(string) $order->id()])) {
            return ;
        }

        $this->orders[(string) $order->id()] = $order;
    }

    public function remove(Order $order): void
    {
        if (!isset($this->orders[(string) $order->id()])) {
            return ;
        }

        unset($this->orders[(string) $order->id()]);
    }

    public function find(OrderId $orderId): ?Order
    {
        return isset($this->orders[(string) $orderId])
            ? $this->orders[(string) $orderId]
            : null
        ;
    }
}