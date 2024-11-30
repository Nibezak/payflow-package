<?php

namespace Payflow\Shipping\Drivers\ShippingMethods;

use Payflow\DataTypes\ShippingOption;
use Payflow\Facades\Pricing;
use Payflow\Models\Product;
use Payflow\Shipping\DataTransferObjects\ShippingOptionRequest;
use Payflow\Shipping\Interfaces\ShippingRateInterface;
use Payflow\Shipping\Models\ShippingRate;

class FlatRate implements ShippingRateInterface
{
    /**
     * The shipping method for context.
     */
    public ShippingRate $shippingRate;

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'Flat Rate Shipping';
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return 'Offer a set price to ship per order total or per line total.';
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

        $subTotal = $cart->lines->sum('subTotal.value');

        $pricing = Pricing::for($shippingRate)->qty($subTotal)->get();

        if (! $pricing->matched) {
            return null;
        }

        return new ShippingOption(
            name: $shippingMethod->name ?: $this->name(),
            description: $shippingMethod->description ?: $this->description(),
            identifier: $shippingRate->getIdentifier(),
            price: $pricing->matched->price,
            taxClass: $shippingRate->getTaxClass(),
            taxReference: $shippingRate->getTaxReference(),
            option: $shippingZone->name,
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
