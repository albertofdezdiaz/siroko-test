<?php

namespace App\Shopping\Domain\Model\Order;

use App\Shopping\Domain\Model\Order\Order;

interface OrderRepository
{
    public function add(Order $order): void;

    public function remove(Order $order): void;

    public function find(OrderId $orderId): ?Order;
}