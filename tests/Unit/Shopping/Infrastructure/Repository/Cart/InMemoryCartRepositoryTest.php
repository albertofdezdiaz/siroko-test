<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Cart;

use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartRepositoryTestcase;

class InMemoryCartRepositoryTest extends CartRepositoryTestcase
{
    public function createSUT(): CartRepository
    {
        return new InMemoryCartRepository();
    }
}