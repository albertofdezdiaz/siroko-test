<?php

namespace App\Shopping\Infrastructure\UI\Api\UpdateItem;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Cart\UpdateItem\UpdateItemRequest;
use App\Shopping\Application\Cart\UpdateItem\UpdateItemService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\UpdateItem\UpdateItemRequest as ApiRequest;
use App\Shopping\Infrastructure\UI\Api\UpdateItem\UpdateItemResponse as ApiResponse;

#[Route('/carts/{cartId}/update-item', name: 'api_carts_update_item', methods: ['PUT'])]
#[OA\Put(
    summary: "Update a product from an active cart",
    description: "Update product from an active cart. Throw error on non active carts or not added products.",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns item updated',
    content: new Model(type: ApiResponse::class)
)]
class UpdateItemController extends AbstractController
{
    public function __invoke(
        string $cartId,
        #[MapRequestPayload()]
        ApiRequest $request,
        UpdateItemService $createCart
    ): JsonResponse {
        $response = $createCart(
            new UpdateItemRequest(
                cartId: new CartId($cartId),
                productId: new ProductId($request->productId),
                quantity: $request->quantity
            )
        );

        return $this->json(
            new UpdateItemResponse(
                cartId: (string) $response->item->cartId(),
                productId: (string) $response->item->productId(),
                quantity: $response->item->quantity(),
            )
        );
    }
}
