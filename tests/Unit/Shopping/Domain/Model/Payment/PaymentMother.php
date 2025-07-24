<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Payment;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Payment\PaymentStatus;

class PaymentMother
{
    public static function fromStatus(string $status)
    {
        return self::from(
            paymentId: PaymentId::generate(),
            cartId: CartId::generate(),
            status: PaymentStatus::tryFrom($status),
        );
    }

    public static function fromStatusAndId(string $status, string $paymentId)
    {
        return self::from(
            paymentId: new PaymentId($paymentId),
            cartId: CartId::generate(),
            status: PaymentStatus::tryFrom($status),
        );
    }

    public static function from(PaymentId $paymentId, CartId $cartId, PaymentStatus $status)
    {
        $cart = new Payment(
            id: $paymentId,
            cartId: $cartId,
            status: $status,
            createdAt: new \DateTimeImmutable('now')
        );

        return $cart;
    }

    public static function random()
    {
        return self::from(
            paymentId: PaymentId::generate(),
            cartId: CartId::generate(),
            status: rand(0, 1) ? PaymentStatus::Pending : PaymentStatus::Paid
        );
    }
}