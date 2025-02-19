<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cookie;
use App\Enums\Cart as CartEnum;

class Cart extends Model
{
//https://www.youtube.com/watch?v=HdF42jiC1Fs
//https://medium.com/@sonamojha2000/submit-form-in-laravel-by-using-ajax-c1888b5d934b
//https://www.youtube.com/watch?v=Qj9CHRy-aqE
    use HasFactory;
    public const COOKIE_LIFETIME_MINUTES = 60;

    protected $fillable = [
        'uuid',
        'user_id',
        'status',
        'data',
    ];

    //TODO read https://arjunamrutiya.medium.com/mastering-cookies-in-laravel-440bef76fddb
    public static function setCartCookie(string $uuid): void
    {
        Cookie::queue(Cookie::make('cart', $uuid, self::COOKIE_LIFETIME_MINUTES));
    }

    public static function getUserCartFromCookies(): ?self
    {
        $uuid = Cookie::get('cart');

        return self::where(['uuid' => $uuid, 'status' => CartEnum::OPEN])->first();
    }

    public static function isCartInCookies(): bool
    {
        return Cookie::has('cart');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
