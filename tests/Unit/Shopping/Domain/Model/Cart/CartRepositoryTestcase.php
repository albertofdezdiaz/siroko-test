<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CartRepositoryTestcase extends KernelTestCase
{
    public function testAddCartToRepository()
    {   
        $repository = $this->createSUT();

        $cart = CartMother::random();

        $this->assertNull($repository->find($cart->id()));

        $repository->add($cart);

        $recoverCart = $repository->find($cart->id());

        $this->assertNotNull($recoverCart);
        $this->assertEquals($cart->id(), $recoverCart->id());
    }

    public function testRemoveCartToRepository()
    {   
        $repository = $this->createSUT();

        $cart = CartMother::random();

        $repository->add($cart);

        $recoverCart = $repository->find($cart->id());

        $this->assertNotNull($recoverCart);
        $this->assertEquals($cart->id(), $recoverCart->id());

        $repository->remove($cart);

        $this->assertNull($repository->find($cart->id()));
    }

    public function testStoredAndRecoverWithItems()
    {
        $repository = $this->createSUT();

        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $itemA = ItemMother::random();
        $cart->addItem($itemA);

        $itemB = ItemMother::random();
        $cart->addItem($itemB);

        $repository->add($cart);

        $recoverCart = $repository->find($cart->id());

        $this->assertNotNull($recoverCart);
        $this->assertNotNull($recoverCart->items());
        $this->assertCount(2, $recoverCart->items());

        $this->assertTrue($recoverCart->items()->contains($itemA));
        $this->assertTrue($recoverCart->items()->contains($itemB));
    }

    abstract protected function createSUT(): CartRepository;
}
