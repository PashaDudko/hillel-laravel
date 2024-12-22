<?php

namespace App\Enums;

enum Cart: string
{
    case OPEN = 'open';
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
