<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\PaymentStatus;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class PaymentRepositoryTestcase extends KernelTestCase
{
    public function testAddPaymentToRepository()
    {   
        $repository = $this->createSUT();

        $cart = PaymentMother::random();

        $this->assertNull($repository->find($cart->id()));

        $repository->add($cart);

        $recoverPayment = $repository->find($cart->id());

        $this->assertNotNull($recoverPayment);
        $this->assertEquals($cart->id(), $recoverPayment->id());
    }

    public function testRemovePaymentToRepository()
    {   
        $repository = $this->createSUT();

        $cart = PaymentMother::random();

        $repository->add($cart);

        $recoverPayment = $repository->find($cart->id());

        $this->assertNotNull($recoverPayment);
        $this->assertEquals($cart->id(), $recoverPayment->id());

        $repository->remove($cart);

        $this->assertNull($repository->find($cart->id()));
    }

    abstract protected function createSUT(): PaymentRepository;
}
