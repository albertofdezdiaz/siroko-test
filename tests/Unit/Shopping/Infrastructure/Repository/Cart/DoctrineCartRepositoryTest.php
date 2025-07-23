<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Cart;

use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartRepositoryTestcase;
use App\Shopping\Infrastructure\Repository\Cart\DoctrineCartRepository;

class DoctrineCartRepositoryTest extends CartRepositoryTestcase
{
    public function createSUT(): CartRepository
    {
        $manager = $this->getContainer()->get(EntityManagerInterface::class);

        return new DoctrineCartRepository($manager);
    }
}