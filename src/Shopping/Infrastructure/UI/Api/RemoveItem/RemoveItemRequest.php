<?php

namespace App\Shopping\Infrastructure\UI\Api\RemoveItem;

use OpenApi\Attributes as OA;

class RemoveItemRequest
{
    public function __construct(
        #[OA\Property(
            type: 'string',
            example: '123e4567-e89b-12d3-a456-426614174000'
        )]
        public string $productId
    )
    {
        
    }
}