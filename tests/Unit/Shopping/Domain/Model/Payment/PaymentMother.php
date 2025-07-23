<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Payment\PaymentStatus;

class PaymentMother
{
    public static function fromStatus(string $status)
    {
        return self::from(
            cartId: PaymentId::generate(),
            status: PaymentStatus::tryFrom($status),
        );
    }

    public static function fromStatusAndId(string $status, string $cartId)
    {
        return self::from(
            cartId: new PaymentId($cartId),
            status: PaymentStatus::tryFrom($status),
        );
    }

    public static function from(PaymentId $cartId, PaymentStatus $status)
    {
        $cart = new Payment(
            id: $cartId,
            status: $status,
            createdAt: new \DateTimeImmutable('now')
        );

        return $cart;
    }

    public static function random()
    {
        return self::from(
            cartId: PaymentId::generate(),
            status: rand(0, 1) ? PaymentStatus::Pending : PaymentStatus::Paid
        );
    }
}