<?php

namespace App\Shopping\Infrastructure\UI\Api\AddItem;

use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Shopping\Domain\Model\Cart\CartId;
use App\Shopping\Domain\Model\Cart\ProductId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shopping\Application\Cart\AddItem\AddItemRequest;
use App\Shopping\Application\Cart\AddItem\AddItemService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shopping\Infrastructure\UI\Api\AddItem\AddItemRequest as ApiRequest;
use App\Shopping\Infrastructure\UI\Api\AddItem\AddItemResponse as ApiResponse;

#[Route('/carts/{cartId}/add-item', name: 'api_carts_add_item', methods: ['PUT'])]
#[OA\Put(
    summary: "Add a product to an active cart",
    description: "Add quantity units of product to an active cart. Throw error on non active carts.",
    tags: ['Shopping']
)]
#[OA\Response(
    response: 200,
    description: 'Returns id of new cart',
    content: new Model(type: ApiResponse::class)
)]
class AddItemController extends AbstractController
{
    public function __invoke(
        string $cartId,
        #[MapRequestPayload()]
        ApiRequest $request,
        AddItemService $createCart
    ): JsonResponse {
        $response = $createCart(
            new AddItemRequest(
                cartId: new CartId($cartId),
                productId: new ProductId($request->productId),
                quantity: $request->quantity
            )
        );

        return $this->json(
            new AddItemResponse(
                cartId: (string) $response->item->cartId(),
                productId: (string) $response->item->productId(),
                quantity: $response->item->quantity(),
            )
        );
    }
}
