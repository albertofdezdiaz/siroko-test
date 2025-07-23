<?php

namespace App\Tests\Unit\Shopping\Application\Cart\View;

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
use App\Shopping\Application\Cart\View\ViewCartRequest;
use App\Shopping\Application\Cart\View\ViewCartService;
use App\Shopping\Application\Cart\View\ViewCartResponse;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;

class ViewCartServiceTest extends TestCase
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

        $request = new ViewCartRequest(
            cartId: $cart->id()
        );

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof ViewCartResponse);
        $this->assertNotNull($result->cart);
    }

    public function testViewCartWhenCartIsNotFoundThrowException()
    {
        $this->expectException(CartNotFoundException::class);

        $repository = new InMemoryCartRepository();

        $cartId = CartId::generate();

        $sut = $this->createSUT($repository);

        $request = new ViewCartRequest(
            cartId: $cartId
        );

        $sut($request);
    }

    private function createSUT($repository)
    {
        return new ViewCartService($repository);
    }
}