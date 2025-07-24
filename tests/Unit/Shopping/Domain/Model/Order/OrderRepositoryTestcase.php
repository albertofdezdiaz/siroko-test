<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Order;

use App\Shopping\Domain\Model\Order\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class OrderRepositoryTestcase extends KernelTestCase
{
    public function testAddOrderToRepository()
    {   
        $repository = $this->createSUT();

        $order = OrderMother::random();

        $this->assertNull($repository->find($order->id()));

        $repository->add($order);

        $recoverOrder = $repository->find($order->id());

        $this->assertNotNull($recoverOrder);
        $this->assertEquals($order->id(), $recoverOrder->id());
    }

    public function testRemoveOrderToRepository()
    {   
        $repository = $this->createSUT();

        $order = OrderMother::random();

        $repository->add($order);

        $recoverOrder = $repository->find($order->id());

        $this->assertNotNull($recoverOrder);
        $this->assertEquals($order->id(), $recoverOrder->id());

        $repository->remove($order);

        $this->assertNull($repository->find($order->id()));
    }

    abstract protected function createSUT(): OrderRepository;
}
