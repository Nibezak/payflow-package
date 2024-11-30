<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Base\Addressable;
use Payflow\Models\Cart;
use Payflow\Models\CartAddress;

class AddAddress extends AbstractAction
{
    protected $fillableAttributes = [
        'country_id',
        'title',
        'first_name',
        'last_name',
        'company_name',
        'line_one',
        'line_two',
        'line_three',
        'city',
        'state',
        'postcode',
        'delivery_instructions',
        'contact_email',
        'contact_phone',
        'meta',
    ];

    /**
     * Execute the action.
     *
     * @param  \Payflow\Models\CartLine  $cartLine
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \Payflow\Models\CartLine
     */
    public function execute(
        Cart $cart,
        array|Addressable $address,
        string $type
    ): self {
        // Do we already have an address for this type?
        $cart->addresses()->whereType($type)->delete();

        if (is_array($address)) {
            $cartAddress = new CartAddress($address);
        }

        if ($address instanceof Addressable) {
            $cartAddress = new CartAddress(
                $address->only($this->fillableAttributes)
            );
        }

        // Force the type.
        $cartAddress->type = $type;
        $cartAddress->cart_id = $cart->id;
        $cartAddress->save();

        return $this;
    }
}
