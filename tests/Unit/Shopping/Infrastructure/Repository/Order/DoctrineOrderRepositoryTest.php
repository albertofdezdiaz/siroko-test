<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Order;

use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Order\OrderRepository;
use App\Tests\Unit\Shopping\Domain\Model\Order\OrderRepositoryTestcase;
use App\Shopping\Infrastructure\Repository\Order\DoctrineOrderRepository;

class DoctrineOrderRepositoryTest extends OrderRepositoryTestcase
{
    public function createSUT(): OrderRepository
    {
        $manager = $this->getContainer()->get(EntityManagerInterface::class);

        return new DoctrineOrderRepository($manager);
    }
}