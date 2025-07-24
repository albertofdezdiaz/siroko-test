<?php

namespace App\Shopping\Application\Payment\Pay;

use App\Shopping\Domain\Model\Payment\Payment;
use App\Shopping\Domain\Model\Payment\PaymentId;
use App\Shopping\Domain\Model\Payment\PaymentRepository;
use App\Shopping\Domain\Model\Cart\CartNotFoundException;
use App\Shopping\Domain\Model\Payment\PaymentNotFoundException;

class PayPaymentService
{
    public function __construct(private PaymentRepository $paymentRepository)
    {
    }

    public function __invoke(PayPaymentRequest $request): PayPaymentResponse
    {
        $payment = $this->paymentRepository->find($request->paymentId);

        if (null === $payment) {
            throw new PaymentNotFoundException($request->paymentId);
        }

        $payment->pay();

        $this->paymentRepository->add($payment);

        return new PayPaymentResponse(
            paymentId: $payment->id(), 
            cartId: $payment->cartId(),
            status: $payment->status()
        );
    }
}