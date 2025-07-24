<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ItemAdded;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartCreated;
use App\Shopping\Domain\Model\Cart\ItemRemoved;
use App\Shopping\Domain\Model\Cart\ItemUpdated;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Cart\CartProcessed;
use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Shopping\Domain\Model\Cart\ItemNotFoundException;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Domain\Model\Cart\NonActiveCartException;
use App\Tests\Unit\Shopping\Domain\Model\Payment\PaymentMother;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;

class CartTest extends TestCase
{
    private $spySubscriber;

    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();

        $this->spySubscriber = new SpyDomainEventSubscriber();
    }

    public function testCreate()
    {
        $cart = new Cart(
            id: CartId::generate(),
        );

        $cart->create();
        
        $this->assertEquals(CartStatus::Active, $cart->status());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof CartCreated);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
    }

    public function testAddItem()
    {
        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->addItem($item);

        $this->assertCount(1, $cart->items());
        $this->assertTrue($cart->items()->contains($item));

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemAdded);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($item->equals($lastEvent->item()));
    }

    public function testAddItemOnNonActiveCart()
    {
        $this->expectException(NonActiveCartException::class);

        $cart = CartMother::fromStatus(
            status: CartStatus::Processed->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->addItem($item);
    }

    public function testRemoveItem()
    {
        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->addItem($item);

        $this->assertCount(1, $cart->items());

        $cart->removeItem($item->productId());

        $this->assertCount(0, $cart->items());
        $this->assertFalse($cart->items()->contains($item));

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemRemoved);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($item->equals($lastEvent->item()));
    }

    public function testRemoveItemOnNonActiveCart()
    {
        $this->expectException(NonActiveCartException::class);

        $cart = CartMother::fromStatus(
            status: CartStatus::Processed->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->removeItem($item->productId());
    }

    public function testRemoveItemOnNotAddedItem()
    {
        $this->expectException(ItemNotFoundException::class);

        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->removeItem($item->productId());
    }

    public function testUpdateItem()
    {
        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->addItem($item);

        $this->assertCount(1, $cart->items());

        $cart->updateItem($item->productId(), 5);

        $this->assertCount(1, $cart->items());
        
        $this->assertEquals(5, $cart->items()->findCombinable($item)?->quantity());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemUpdated);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($item->productId()->equals($lastEvent->item()->productId()));
    }

    public function testUpdateItemOnNonActiveCart()
    {
        $this->expectException(NonActiveCartException::class);

        $cart = CartMother::fromStatus(
            status: CartStatus::Processed->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->items()->add($item);

        $cart->updateItem($item->productId(), 1);
    }

    public function testUpdateItemOnNotAddedItem()
    {
        $this->expectException(ItemNotFoundException::class);

        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->updateItem($item->productId(), 1);
    }

    public function testUpdateItemToZeroQuantityEqualsToRemove()
    {
        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $item = ItemMother::from(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $cart->addItem($item);

        $this->assertCount(1, $cart->items());

        $cart->updateItem($item->productId(), 0);

        $this->assertCount(0, $cart->items());
        
        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemRemoved);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($item->equals($lastEvent->item()));
    }

    public function testProcess()
    {
        $cart = CartMother::fromStatus(
            status: CartStatus::Active->value
        );

        $payment = PaymentMother::from(
            paymentId: PaymentId::generate(),
            cartId: $cart->id(),
            status: PaymentStatus::Paid
        );

        $cart->process($payment);

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof CartProcessed);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($payment->id()->equals($lastEvent->paymentId()));
    }
}