<?php

namespace App\Shopping\Infrastructure\Repository\Payment;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
class InMemoryPaymentRepository implements PaymentRepository
{
    public function __construct(private array $payments = [])
    {

    }

    public function add(Payment $payment): void
    {
        if (isset($this->payments[(string) $payment->id()])) {
            return ;
        }

        $this->payments[(string) $payment->id()] = $payment;
    }

    public function remove(Payment $payment): void
    {
        if (!isset($this->payments[(string) $payment->id()])) {
            return ;
        }

        unset($this->payments[(string) $payment->id()]);
    }

    public function find(PaymentId $paymentId): ?Payment
    {
        return isset($this->payments[(string) $paymentId])
            ? $this->payments[(string) $paymentId]
            : null
        ;
    }
}