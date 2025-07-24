<?php

namespace App\Tests\Unit\Shopping\Domain\Model\Order;

use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Order\Order;
use App\Shopping\Domain\Model\Order\OrderId;
use App\Shopping\Domain\Model\Payment\PaymentId;

class OrderMother
{
    public static function from(OrderId $orderId, CartId $cartId, PaymentId $paymentId)
    {
        $cart = new Order(
            id: $orderId,
            cartId: $cartId,
            paymentId: $paymentId,
            createdAt: new \DateTimeImmutable('now')
        );

        return $cart;
    }

    public static function random()
    {
        return self::from(
            orderId: OrderId::generate(),
            cartId: CartId::generate(),
            paymentId: PaymentId::generate(),
        );
    }
}