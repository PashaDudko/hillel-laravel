<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Enums\Cart as CartEnum;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'product_id',
        'quantity',
        'status'
    ];

    public static function isSessionHasCart(): bool
    {
        return Session::has('cart');
    }

    public static function getCartFromSession(string $uuid): ?self
    {
        return self::findOrFail(['uuid' => $uuid, 'status' => CartEnum::OPEN]);
    }
}
