<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Cart;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ItemAdded;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartCreated;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Domain\Model\Cart\NonActiveCartException;
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
}