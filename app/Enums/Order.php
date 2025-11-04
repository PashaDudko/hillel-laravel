<?php

namespace App\Enums;

enum Order: string
{
    case CREATED = 'created';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
    case CONFIRMED = 'confirmed';
    case DELIVERED = 'delivered';
    case RECEIVED = 'received';
    case CLOSED = 'closed';

//    public static function values(): array
//    {
//        $values = [];
//
//        foreach (self::cases() as $props) {
//            $values[] = $props->value;
//        }
//
//        return $values;
//    }
}
