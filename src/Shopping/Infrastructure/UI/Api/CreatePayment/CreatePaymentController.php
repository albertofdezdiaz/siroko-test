<?php

namespace App\Shopping\Infrastructure\UI\Api\CreatePayment;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Shopping\Domain\Model\Cart\CartId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Shopping\Application\Payment\Create\CreatePaymentRequest;
use App\Shopping\Application\Payment\Create\CreatePaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\CreatePayment\CreatePaymentResponse;
use App\Shopping\Infrastructure\UI\Api\CreatePayment\CreatePaymentRequest as ApiRequest;

#[Route('/payments', name: 'api_payments_create', methods: ['POST'])]
#[OA\Post(
    summary: "Create a new payment",
    description: "Create a new payment for an active cart. Throws errors on non active carts",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns id of new payment',
    content: new Model(type: CreatePaymentResponse::class)
)]
class CreatePaymentController extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload()]
        ApiRequest $request,
        CreatePaymentService $createPayment
    ): JsonResponse {
        $createResponse = $createPayment(
            new CreatePaymentRequest(
                new CartId($request->cartId)
            )
        );

        return $this->json(
            new CreatePaymentResponse(
                cartId: (string) $createResponse->cartId,
                paymentId: (string) $createResponse->paymentId
            )
        );
    }
}
