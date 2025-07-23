<?php

namespace App\Shopping\Infrastructure\UI\Api\UpdateItem;

use OpenApi\Attributes as OA;

class UpdateItemRequest
{
    public function __construct(
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