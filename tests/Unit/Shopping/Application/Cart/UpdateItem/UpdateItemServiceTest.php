<?php

namespace App\Tests\Unit\Shopping\Application\Cart\UpdateItem;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\Item;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\ItemUpdated;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Application\Cart\UpdateItem\UpdateItemRequest;
use App\Shopping\Application\Cart\UpdateItem\UpdateItemService;
use App\Shopping\Application\Cart\UpdateItem\UpdateItemResponse;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;

class UpdateItemServiceTest extends TestCase
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

        $productId = ProductId::generate();
        $cart = CartMother::fromStatus(CartStatus::Active->value);

        $item = new Item(
            quantity: 1,
            productId: $productId,
            cartId: $cart->id()
        );

        $cart->addItem(
            $item
        );

        $repository->add($cart);

        $sut = $this->createSUT($repository);

        $request = new UpdateItemRequest(
            cartId: $cart->id(),
            productId: $productId,
            quantity: 2
        );

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof UpdateItemResponse);
        $this->assertNotNull($result->item);

        $cartPersisted = $repository->find($cart->id());

        $this->assertNotNull($cartPersisted);
        $this->assertTrue($cartPersisted instanceof Cart);
        $this->assertEquals(CartStatus::Active, $cartPersisted->status());
        $this->assertCount(1, $cartPersisted->items());

        $this->assertEquals(2, $cartPersisted->items()->findCombinable($item)->quantity());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemUpdated);
    }

    public function testUpdateItemWhenCartIsNotFoundThrowException()
    {
        $this->expectException(CartNotFoundException::class);

        $repository = new InMemoryCartRepository();

        $cartId = CartId::generate();

        $sut = $this->createSUT($repository);

        $request = new UpdateItemRequest(
            cartId: $cartId,
            productId: ProductId::generate(),
            quantity: 2
        );

        $sut($request);
    }

    private function createSUT($repository)
    {
        return new UpdateItemService($repository);
    }
}