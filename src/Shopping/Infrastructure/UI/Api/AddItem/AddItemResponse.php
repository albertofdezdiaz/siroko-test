<?php

namespace App\Shopping\Infrastructure\UI\Api\AddItem;

use OpenApi\Attributes as OA;

class AddItemResponse
{
    public function __construct(
        #[OA\Property(
            type: 'string',
            example: '123e4567-e89b-12d3-a456-426614174000'
        )]
        public string $cartId,
        
        #[OA\Property(
            type: 'string',
            example: '123e4567-e89b-12d3-a456-426614174000'
        )]
        public string $productId,

        #[OA\Property(
            type: 'string',
            example: 1
        )]
        public int $quantity,
    )
    {
        
    }
}