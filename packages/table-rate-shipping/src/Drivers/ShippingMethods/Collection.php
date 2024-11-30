<?php

namespace Payflow\Shipping\Drivers\ShippingMethods;

use Payflow\DataTypes\Price;
use Payflow\DataTypes\ShippingOption;
use Payflow\Models\Product;
use Payflow\Shipping\DataTransferObjects\ShippingOptionRequest;
use Payflow\Shipping\Interfaces\ShippingRateInterface;
use Payflow\Shipping\Models\ShippingRate;

class Collection implements ShippingRateInterface
{
    /**
     * The shipping rate for context.
     */
    public ShippingRate $shippingRate;

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'Collection';
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return 'Allow customers to pick up their orders in store';
    }

    public function resolve(ShippingOptionRequest $shippingOptionRequest): ?ShippingOption
    {
        $shippingRate = $shippingOptionRequest->shippingRate;
        $shippingMethod = $shippingRate->shippingMethod;
        $shippingZone = $shippingRate->shippingZone;
        $cart = $shippingOptionRequest->cart;

        // Do we have any products in our exclusions list?
        // If so, we do not want to return this option regardless.
        $productIds = $cart->lines->load('purchasable')->pluck('purchasable.product_id');

        $hasExclusions = $shippingZone->shippingExclusions()
            ->whereHas('exclusions', function ($query) use ($productIds) {
                $query->wherePurchasableType(Product::morphName())
                    ->whereIn('purchasable_id', $productIds);
            })->exists();

        if ($hasExclusions) {
            return null;
        }

        return new ShippingOption(
            name: $shippingMethod->name,
            description: $shippingMethod->description,
            identifier: $shippingRate->getIdentifier(),
            price: new Price(
                value: 0,
                currency: $cart->currency,
                unitQty: 1
            ),
            taxClass: $shippingRate->getTaxClass(),
            taxReference: $shippingRate->getTaxReference(),
            option: $shippingZone->name,
            collect: true,
            meta: ['shipping_zone' => $shippingZone->name]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function on(ShippingRate $shippingRate): self
    {
        $this->shippingRate = $shippingRate;

        return $this;
    }
}
