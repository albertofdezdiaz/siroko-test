<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Payment;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Shopping\Domain\Model\Payment\PaymentCreated;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Tests\Unit\Shared\Domain\Event\SpyDomainEventSubscriber;

class PaymentTest extends TestCase
{
    private $spySubscriber;

    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();

        $this->spySubscriber = new SpyDomainEventSubscriber();
    }

    public function testCreate()
    {
        $cart = CartMother::fromStatus(CartStatus::Active->value);

        $payment = new Payment(
            id: PaymentId::generate(),
        );

        $payment->create($cart);
        
        $this->assertEquals(PaymentStatus::Pending, $payment->status());

        $lastEvent = $this->spySubscriber->lastEvent();

        $this->assertNotNull($lastEvent);
        $this->assertTrue($lastEvent instanceof PaymentCreated);
        $this->assertTrue($payment->id()->equals($lastEvent->paymentId()));
    }
}