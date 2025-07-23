<?php

namespace App\Shopping\Domain\Model\Cart;

enum CartStatus: string
{
    case Active = 'active';
    case Processed = 'processed';
}