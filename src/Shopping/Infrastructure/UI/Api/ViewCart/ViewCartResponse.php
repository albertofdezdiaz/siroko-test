<?php

namespace App\Shopping\Infrastructure\UI\Api\ViewCart;

use OpenApi\Attributes as OA;
use App\Shopping\Domain\Model\Cart\Item;
use Nelmio\ApiDocBundle\Attribute\Model;

class ViewCartResponse
{
    public function __construct(
        #[OA\Property(
            type: 'string',
            example: '123e4567-e89b-12d3-a456-426614174000'
        )]
        public string $cartId,
        
        #[OA\Property(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Item::class))
        )]
        public array $items
    )
    {
        
    }
}