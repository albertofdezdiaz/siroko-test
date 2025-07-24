<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class PaymentRepositoryTestcase extends KernelTestCase
{
    public function testAddPaymentToRepository()
    {   
        $repository = $this->createSUT();

        $payment = PaymentMother::random();

        $this->assertNull($repository->find($payment->id()));

        $repository->add($payment);

        $recoverPayment = $repository->find($payment->id());

        $this->assertNotNull($recoverPayment);
        $this->assertEquals($payment->id(), $recoverPayment->id());
    }

    public function testRemovePaymentToRepository()
    {   
        $repository = $this->createSUT();

        $payment = PaymentMother::random();

        $repository->add($payment);

        $recoverPayment = $repository->find($payment->id());

        $this->assertNotNull($recoverPayment);
        $this->assertEquals($payment->id(), $recoverPayment->id());

        $repository->remove($payment);

        $this->assertNull($repository->find($payment->id()));
    }

    abstract protected function createSUT(): PaymentRepository;
}
