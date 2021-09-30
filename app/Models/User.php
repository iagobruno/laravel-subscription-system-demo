<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Helpers\Stripe;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [];

    public function getCurrentPlanInfos()
    {
        if (!$this->subscribed()) {
            return null;
        }

        $productId = $this->subscription('default')->items->first()->stripe_product;
        return Stripe::client()->products->retrieve($productId);
    }
}
