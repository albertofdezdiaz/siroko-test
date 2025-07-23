<?php

namespace App\Tests\Unit\Shopping\Application\Cart\RemoveItem;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\ItemRemoved;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Cart\ItemNotFoundException;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Application\Cart\RemoveItem\RemoveItemRequest;
use App\Shopping\Application\Cart\RemoveItem\RemoveItemService;
use App\Shopping\Application\Cart\RemoveItem\RemoveItemResponse;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;

class RemoveItemServiceTest extends TestCase
{
    private $spySubscriber;

    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();

        $this->spySubscriber = new SpyDomainEventSubscriber();
    }

    public function testUseCase()
    {
        $repository = new InMemoryCartRepository();

        $cart = CartMother::fromStatus(CartStatus::Active->value);
        $productId = ProductId::generate();        

        $cart->addItem(
            new Item(
                cartId: $cart->id(),
                productId: $productId,
                quantity: 2
            )
        );

        $repository->add($cart);

        $sut = $this->createSUT($repository);

        $request = new RemoveItemRequest(
            cartId: $cart->id(),
            productId: $productId
        );

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof RemoveItemResponse);
        $this->assertNotNull($result->item);

        $cartPersisted = $repository->find($cart->id());

        $this->assertNotNull($cartPersisted);
        $this->assertTrue($cartPersisted instanceof Cart);
        $this->assertEquals(CartStatus::Active, $cartPersisted->status());
        $this->assertCount(0, $cartPersisted->items());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemRemoved);
        $this->assertTrue($cartPersisted->id()->equals($lastEvent->cartId()));
    }

    public function testRemoveItemWhenCartIsNotFoundThrowException()
    {
        $this->expectException(CartNotFoundException::class);

        $repository = new InMemoryCartRepository();

        $cartId = CartId::generate();

        $sut = $this->createSUT($repository);

        $request = new RemoveItemRequest(
            cartId: $cartId,
            productId: ProductId::generate()
        );

        $sut($request);
    }

    public function testRemoveItemWhenItemIsNotFoundThrowException()
    {
        $this->expectException(ItemNotFoundException::class);

        $repository = new InMemoryCartRepository();

        $cart = CartMother::fromStatus(CartStatus::Active->value);
        $repository->add($cart);

        $sut = $this->createSUT($repository);

        $request = new RemoveItemRequest(
            cartId: $cart->id(),
            productId: ProductId::generate()
        );

        $sut($request);
    }

    private function createSUT($repository)
    {
        return new RemoveItemService($repository);
    }
}