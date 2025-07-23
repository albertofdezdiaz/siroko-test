<?php

namespace App\Shopping\Application\Payment\Create;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Cart\CartRepository;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;

class CreatePaymentService
{
    public function __construct(private PaymentRepository $paymentRepository, private CartRepository $cartRepository)
    {
    }

    public function __invoke(CreatePaymentRequest $request): CreatePaymentResponse
    {
        $cart = $this->cartRepository->find($request->cartId);

        if (null === $cart) {
            throw new CartNotFoundException($request->cartId);
        }

        $id = PaymentId::generate();

        $payment = new Payment($id);
        $payment->create($cart);

        $this->paymentRepository->add($payment);

        return new CreatePaymentResponse(
            cartId: $cart->id(), 
            paymentId: $payment->id(),
            status: $payment->status()
        );
    }
}