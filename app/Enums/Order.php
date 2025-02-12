<?php

namespace App\Enums;

enum Order: string
{
    case CLOSED = 'closed';
    case CREATED = 'created';
    case RECEIVED = 'received';
    case CONFIRMED = 'confirmed';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

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
