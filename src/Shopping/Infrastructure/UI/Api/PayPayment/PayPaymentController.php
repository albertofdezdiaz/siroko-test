<?php

namespace App\Shopping\Infrastructure\UI\Api\PayPayment;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\Routing\Attribute\Route;
use App\Shopping\Domain\Model\Payment\PaymentId;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Payment\Pay\PayPaymentRequest;
use App\Shopping\Application\Payment\Pay\PayPaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\PayPayment\PayPaymentResponse;

#[Route('/payments/{paymentId}', name: 'api_payments_pay', methods: ['PUT'])]
#[OA\Post(
    summary: "Create a new payment",
    description: "Create a new payment for an active cart. Throws errors on non active carts",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns id of new payment',
    content: new Model(type: PayPaymentResponse::class)
)]
class PayPaymentController extends AbstractController
{
    public function __invoke(
        $paymentId,
        PayPaymentService $createPayment
    ): JsonResponse {
        $response = $createPayment(
            new PayPaymentRequest(
                new PaymentId($paymentId)
            )
        );

        return $this->json(
            new PayPaymentResponse(
                cartId: (string) $response->cartId,
                paymentId: (string) $response->paymentId,
                status: $response->status->value
            )
        );
    }
}
