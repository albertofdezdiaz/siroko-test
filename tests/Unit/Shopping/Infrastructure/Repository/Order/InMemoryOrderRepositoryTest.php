<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Order;

use App\Shopping\Domain\Model\Order\OrderRepository;
use App\Shopping\Infrastructure\Repository\Order\InMemoryOrderRepository;
use App\Tests\Unit\Shopping\Domain\Model\Order\OrderRepositoryTestcase;

class InMemoryOrderRepositoryTest extends OrderRepositoryTestcase
{
    public function createSUT(): OrderRepository
    {
        return new InMemoryOrderRepository();
    }
}