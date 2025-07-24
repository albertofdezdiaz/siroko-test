<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Payment;

use App\Shopping\Domain\Model\Payment\PaymentRepository;
use App\Shopping\Infrastructure\Repository\Payment\InMemoryPaymentRepository;
use App\Tests\Unit\Shopping\Domain\Model\Payment\PaymentRepositoryTestcase;

class InMemoryPaymentRepositoryTest extends PaymentRepositoryTestcase
{
    public function createSUT(): PaymentRepository
    {
        return new InMemoryPaymentRepository();
    }
}