<?php

namespace Payflow\Stripe\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Models\Cart;
use Payflow\Stripe\Enums\CancellationReason;
use Payflow\Stripe\MockClient;
use Stripe\ApiRequestor;

/**
 * @method static getClient(): \Stripe\StripeClient
 * @method static getCartIntentId(Cart $cart): ?string
 * @method static fetchOrCreateIntent(Cart $cart, array $createOptions): ?string
 * @method static createIntent(\Payflow\Models\Cart $cart, array $createOptions): \Stripe\PaymentIntent
 * @method static syncIntent(\Payflow\Models\Cart $cart): void
 * @method static updateIntent(\Payflow\Models\Cart $cart, array $values): void
 * @method static cancelIntent(\Payflow\Models\Cart $cart, CancellationReason $reason): void
 * @method static updateShippingAddress(\Payflow\Models\Cart $cart): void
 * @method static getCharges(string $paymentIntentId): \Illuminate\Support\Collection
 * @method static getCharge(string $chargeId): \Stripe\Charge
 * @method static buildIntent(int $value, string $currencyCode, \Payflow\Models\CartAddress $shipping): \Stripe\PaymentIntent
 */
class Stripe extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payflow:stripe';
    }

    public static function fake(): void
    {
        $mockClient = new MockClient;
        ApiRequestor::setHttpClient($mockClient);
    }
}
