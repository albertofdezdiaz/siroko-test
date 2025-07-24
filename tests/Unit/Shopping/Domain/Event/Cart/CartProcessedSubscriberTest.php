<?php

namespace App\Tests\Unit\Shopping\Domain\Event\Cart;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Cart\CartProcessed;
use App\Shopping\Domain\Model\Order\OrderCreated;
use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Domain\Event\Cart\CartProcessedSubscriber;
use App\Tests\Unit\Shopping\Domain\Model\Payment\PaymentMother;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;
use App\Shopping\Infrastructure\Repository\Order\InMemoryOrderRepository;

class CartProcessedSubscriberTest extends TestCase
{
    private $spySubscriber;

    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();

        $this->spySubscriber = new SpyDomainEventSubscriber();
    }

    public function isSubscribedToReturnsTrueIfAccountRemovedEventIsPassed()
    {
        $repository = new InMemoryOrderRepository();
        $cart = CartMother::fromStatus(
            CartStatus::Processed->value
        );

        $payment = PaymentMother::from(
            paymentId: PaymentId::generate(),
            cartId: $cart->id(),
            status: PaymentStatus::Paid
        );

        $sut = $this->createSUT($repository);

        $event = new CartProcessed(
            paymentId: $payment->id(),
            cartId: $cart->id(),
        );

        $result = $sut->isSubscribedTo($event);

        $this->assertTrue($result);
    }

    public function testHandleCallsRemoveUserService()
    {
        $repository = new InMemoryOrderRepository();

        $cart = CartMother::fromStatus(
            CartStatus::Processed->value
        );

        $payment = PaymentMother::from(
            paymentId: PaymentId::generate(),
            cartId: $cart->id(),
            status: PaymentStatus::Paid
        );

        $sut = $this->createSUT($repository);

        $event = new CartProcessed(
            paymentId: $payment->id(),
            cartId: $cart->id(),
        );

        $sut->handle($event);

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof OrderCreated);
        $this->assertTrue($cart->id()->equals($lastEvent->cartId()));
        $this->assertTrue($payment->id()->equals($lastEvent->paymentId()));
    }

    private function createSUT($repository)
    {
        return new CartProcessedSubscriber(
            repository: $repository
        );
    }
}