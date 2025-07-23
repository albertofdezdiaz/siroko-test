<?php

namespace App\Shopping\Domain\Model\Payment;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
}