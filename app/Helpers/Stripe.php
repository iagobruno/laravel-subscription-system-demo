<?php

namespace App\Helpers;

use \Stripe\StripeClient;

class Stripe
{

    static function client()
    {
        $key = config('services.stripe.secret');
        return new StripeClient($key);
    }

    static function getPlans()
    {
        $plans = Stripe::client()->plans->all()->data;

        foreach ($plans as $plan) {
            $product = Stripe::client()->products->retrieve(
                $plan->product,
                []
            );
            $plan->product = $product;
        }

        return $plans;
    }
}
