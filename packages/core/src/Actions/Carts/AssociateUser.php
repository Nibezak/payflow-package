<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Base\PayflowUser;
use Payflow\Models\Cart;

class AssociateUser extends AbstractAction
{
    /**
     * Execute the action
     *
     * @param  string  $policy
     */
    public function execute(Cart $cart, PayflowUser $user, $policy = 'merge'): self
    {
        if ($policy == 'merge') {
            $userCart = Cart::whereUserId($user->getKey())->active()->unMerged()->latest()->first();
            if ($userCart) {
                app(MergeCart::class)->execute($cart, $userCart);
            }
        }

        if ($policy == 'override') {
            $userCart = Cart::whereUserId($user->getKey())->active()->unMerged()->latest()->first();
            if ($userCart && $userCart->id != $cart->id) {
                $userCart->update([
                    'merged_id' => $userCart->id,
                ]);
            }
        }

        $cart->update([
            'user_id' => $user->getKey(),
            'customer_id' => $user->latestCustomer()?->getKey(),
        ]);

        return $this;
    }
}
