<?php

namespace App\Shopping\Infrastructure\UI\Api\ViewCart;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Shopping\Domain\Model\Cart\CartId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Cart\View\ViewCartRequest;
use App\Shopping\Application\Cart\View\ViewCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\ViewCart\ViewCartResponse as ApiResponse;

#[Route('/carts/{cartId}', name: 'api_carts_view', methods: ['GET'])]
#[OA\Get(
    summary: "Get items of a cart",
    description: "Get items of a cart",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns cart info and items',
    content: new Model(type: ApiResponse::class)
)]
class ViewCartController extends AbstractController
{
    public function __invoke(
        string $cartId,
        ViewCartService $createCart
    ): JsonResponse {
        $response = $createCart(
            new ViewCartRequest(
                cartId: new CartId($cartId)
            )
        );

        return $this->json(
            new ViewCartResponse(
                cartId: (string) $response->cart->id(),
                items: $response->cart->items()->toArray()
            )
        );
    }
}
