<?php

namespace App\Tests\Unit\Shopping\Domain\Event\Payment;

use PHPUnit\Framework\TestCase;
use App\Shopping\Domain\Model\Cart\CartStatus;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shared\Domain\Event\DomainEventPublisher;
use App\Shopping\Domain\Model\Payment\PaymentPaid;
use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Tests\Unit\Shopping\Domain\Model\Cart\CartMother;
use App\Shopping\Domain\Event\Payment\PaymentPaidSubscriber;
use App\Tests\Unit\Shopping\Domain\Model\Payment\PaymentMother;
use App\Shopping\Infrastructure\Repository\Cart\InMemoryCartRepository;
use App\Shopping\Infrastructure\Repository\Payment\InMemoryPaymentRepository;

class PaymentPaidSubscriberTest extends TestCase
{
    protected function setUp(): void
    {
        DomainEventPublisher::instance()->reset();
    }

    public function isSubscribedToReturnsTrueIfAccountRemovedEventIsPassed()
    {
        $cartRepository = new InMemoryCartRepository();
        $paymentRepository = new InMemoryPaymentRepository();

        $cart = CartMother::fromStatus(
            CartStatus::Active->value
        );

        $cartRepository->add($cart);

        $payment = PaymentMother::from(
            paymentId: PaymentId::generate(),
            cartId: $cart->id(),
            status: PaymentStatus::Paid
        );

        $paymentRepository->add($payment);

        $sut = $this->createSUT($cartRepository, $paymentRepository);

        $event = new PaymentPaid(
            paymentId: $payment->id()
        );

        $result = $sut->isSubscribedTo($event);

        $this->assertTrue($result);
    }

    public function testHandleCallsRemoveUserService()
    {
        $cartRepository = new InMemoryCartRepository();
        $paymentRepository = new InMemoryPaymentRepository();

        $cart = CartMother::fromStatus(
            CartStatus::Active->value
        );

        $cartRepository->add($cart);

        $payment = PaymentMother::from(
            paymentId: PaymentId::generate(),
            cartId: $cart->id(),
            status: PaymentStatus::Paid
        );

        $paymentRepository->add($payment);

        $sut = $this->createSUT($cartRepository, $paymentRepository);

        $event = new PaymentPaid(
            paymentId: $payment->id()
        );

        $sut->handle($event);

        $this->assertEquals(CartStatus::Processed, $cart->status());
    }

    private function createSUT($cartRepository, $paymentRepository)
    {
        return new PaymentPaidSubscriber(
            cartRepository: $cartRepository,
            paymentRepository: $paymentRepository
        );
    }
}