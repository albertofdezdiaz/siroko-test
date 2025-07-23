<?php

namespace App\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;

interface PaymentRepository
{
    public function add(Payment $payment): void;

    public function remove(Payment $payment): void;

    public function find(PaymentId $paymentId): ?Payment;
}