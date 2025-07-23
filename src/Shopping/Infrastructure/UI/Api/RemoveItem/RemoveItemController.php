<?php

namespace App\Shopping\Infrastructure\UI\Api\RemoveItem;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Cart\RemoveItem\RemoveItemRequest;
use App\Shopping\Application\Cart\RemoveItem\RemoveItemService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\RemoveItem\RemoveItemRequest as ApiRequest;
use App\Shopping\Infrastructure\UI\Api\RemoveItem\RemoveItemResponse as ApiResponse;

#[Route('/carts/{cartId}/remove-item', name: 'api_carts_remove_item', methods: ['PUT'])]
#[OA\Put(
    summary: "Remove a product from an active cart",
    description: "Remove product from an active cart. Throw error on non active carts or not added products.",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns item removed',
    content: new Model(type: ApiResponse::class)
)]
class RemoveItemController extends AbstractController
{
    public function __invoke(
        string $cartId,
        #[MapRequestPayload()]
        ApiRequest $request,
        RemoveItemService $createCart
    ): JsonResponse {
        $response = $createCart(
            new RemoveItemRequest(
                cartId: new CartId($cartId),
                productId: new ProductId($request->productId)
            )
        );

        return $this->json(
            new RemoveItemResponse(
                cartId: (string) $response->item->cartId(),
                productId: (string) $response->item->productId(),
                quantity: $response->item->quantity(),
            )
        );
    }
}
