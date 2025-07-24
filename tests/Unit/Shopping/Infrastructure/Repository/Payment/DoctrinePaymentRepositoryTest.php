<?php

namespace App\Tests\Unit\Shopping\Infrastructure\Repository\Payment;

use Doctrine\ORM\EntityManagerInterface;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use App\Tests\Unit\Shopping\Domain\Model\Payment\PaymentRepositoryTestcase;
use App\Shopping\Infrastructure\Repository\Payment\DoctrinePaymentRepository;

class DoctrinePaymentRepositoryTest extends PaymentRepositoryTestcase
{
    public function createSUT(): PaymentRepository
    {
        $manager = $this->getContainer()->get(EntityManagerInterface::class);

        return new DoctrinePaymentRepository($manager);
    }
}