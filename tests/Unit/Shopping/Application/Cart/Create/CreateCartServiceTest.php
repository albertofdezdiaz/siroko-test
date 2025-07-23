<?php

namespace App\Tests\Unit\Shopping\Application\Cart\Create;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\Cart;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Cart\CartCreated;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Application\Cart\Create\CreateCartRequest;
use App\Shopping\Application\Cart\Create\CreateCartService;
use App\Shopping\Application\Cart\Create\CreateCartResponse;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;

class CreateCartServiceTest extends TestCase
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

        $sut = $this->createSUT($repository);

        $request = new CreateCartRequest();

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof CreateCartResponse);
        $this->assertNotEmpty((string) $result->cartId);
        $this->assertEquals(36, strlen((string) $result->cartId));

        $cartPersisted = $repository->find($result->cartId);

        $this->assertNotNull($cartPersisted);
        $this->assertTrue($cartPersisted instanceof Cart);
        $this->assertEquals(CartStatus::Active, $cartPersisted->status());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof CartCreated);
        $this->assertTrue($cartPersisted->id()->equals($lastEvent->cartId()));
    }

    private function createSUT($repository)
    {
        return new CreateCartService($repository);
    }
}