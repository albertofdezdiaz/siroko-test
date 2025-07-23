<?php

namespace App\Shopping\Infrastructure\UI\Api\CreateCart;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Cart\Create\CreateCartRequest;
use App\Shopping\Application\Cart\Create\CreateCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\CreateCart\CreateCartResponse;

#[Route('/carts', name: 'api_carts_create', methods: ['POST'])]
#[OA\Post(
    summary: "Create a new cart",
    description: "Create a new empty cart",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns id of new cart',
    content: new Model(type: CreateCartResponse::class)
)]
class CreateCartController extends AbstractController
{
    public function __invoke(
        CreateCartService $createCart
    ): JsonResponse {
        $createResponse = $createCart(
            new CreateCartRequest()
        );

        return $this->json(
            new CreateCartResponse(
                cartId: (string) $createResponse->cartId
            )
        );
    }
}
