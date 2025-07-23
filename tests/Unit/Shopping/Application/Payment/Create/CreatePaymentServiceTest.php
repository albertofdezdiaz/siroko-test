<?php

namespace App\Tests\Unit\Shopping\Application\Payment\Create;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Payment\PaymentCreated;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Application\Payment\Create\CreatePaymentRequest;
use App\Shopping\Application\Payment\Create\CreatePaymentService;
use App\Shopping\Application\Payment\Create\CreatePaymentResponse;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;
use App\Shopping\Infrastructure\Repository\Payment\InMemoryPaymentRepository;

class CreatePaymentServiceTest extends TestCase
{
    private $spySubscriber;

    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();

        $this->spySubscriber = new SpyDomainEventSubscriber();
    }

    public function testUseCase()
    {
        $paymentRepository = new InMemoryPaymentRepository();

        $cart = CartMother::fromStatus(CartStatus::Active->value);

        $cartRepository = new InMemoryCartRepository();

        $cartRepository->add($cart);

        $sut = $this->createSUT($paymentRepository, $cartRepository);

        $request = new CreatePaymentRequest(
            cartId: $cart->id()
        );

        $result = $sut($request);

        $this->assertNotNull($result);
        $this->assertTrue($result instanceof CreatePaymentResponse);
        $this->assertNotEmpty((string) $result->paymentId);
        $this->assertNotEmpty((string) $result->cartId);

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof PaymentCreated);
    }

    private function createSUT($paymentRepository, $cartRepository)
    {
        return new CreatePaymentService(
            paymentRepository: $paymentRepository, 
            cartRepository: $cartRepository
        );
    }
}