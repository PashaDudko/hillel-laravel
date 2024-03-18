<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case CUSTOMER = 'customer';

    public static function values(): array
    {
        $values = [];

        foreach (self::cases() as $props) {
            $values[] = $props->value;
        }

        return $values;
    }
}
