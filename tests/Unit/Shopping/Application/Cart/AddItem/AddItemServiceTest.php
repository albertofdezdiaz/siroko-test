<?php

namespace App\Tests\Unit\Shopping\Application\Cart\AddItem;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\ItemAdded;
use App\Shopping\Domain\Model\Cart\ProductId;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartCreated;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Application\Cart\AddItem\AddItemRequest;
use App\Shopping\Application\Cart\AddItem\AddItemService;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Application\Cart\AddItem\AddItemResponse;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;

class AddItemServiceTest extends TestCase
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

        $repository->add($cart);

        $sut = $this->createSUT($repository);

        $request = new AddItemRequest(
            cartId: $cart->id(),
            productId: ProductId::generate(),
            quantity: 2
        );

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof AddItemResponse);
        $this->assertNotNull($result->item);

        $cartPersisted = $repository->find($cart->id());

        $this->assertNotNull($cartPersisted);
        $this->assertTrue($cartPersisted instanceof Cart);
        $this->assertEquals(CartStatus::Active, $cartPersisted->status());
        $this->assertCount(1, $cartPersisted->items());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof ItemAdded);
        $this->assertTrue($cartPersisted->id()->equals($lastEvent->cartId()));
    }

    private function createSUT($repository)
    {
        return new AddItemService($repository);
    }
}