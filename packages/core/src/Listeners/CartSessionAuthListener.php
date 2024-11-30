<?php

namespace Payflow\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Payflow\Facades\CartSession;
use Payflow\Models\Cart;

class CartSessionAuthListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the login event.
     *
     * @return void
     */
    public function login(Login $event)
    {
        if (! is_payflow_user($event->user)) {
            return;
        }

        $currentCart = CartSession::current();

        if ($currentCart && ! $currentCart->user_id) {
            CartSession::associate(
                $currentCart,
                $event->user,
                config('payflow.cart.auth_policy')
            );
        }

        if (! $currentCart) {
            // Does this user have a cart?
            $userCart = Cart::whereUserId($event->user->getKey())->active()->first();

            if ($userCart) {
                CartSession::use($userCart);
            }
        }
    }

    /**
     * Handle the logout event.
     *
     * @return void
     */
    public function logout(Logout $event)
    {
        if (is_null($event->user) || ! is_payflow_user($event->user)) {
            return;
        }

        CartSession::forget();
    }
}
