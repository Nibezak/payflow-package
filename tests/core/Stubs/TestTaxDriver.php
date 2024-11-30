<?php

namespace Payflow\Tests\Core\Stubs;

use Payflow\Base\Addressable;
use Payflow\Base\Purchasable;
use Payflow\Base\TaxDriver;
use Payflow\Base\ValueObjects\Cart\TaxBreakdown;
use Payflow\Base\ValueObjects\Cart\TaxBreakdownAmount;
use Payflow\DataTypes\Price;
use Payflow\Models\CartLine;
use Payflow\Models\Currency;
use Payflow\Models\ProductVariant;
use Payflow\Models\TaxRateAmount;

class TestTaxDriver implements TaxDriver
{
    /**
     * The taxable shipping address.
     */
    protected ?Addressable $shippingAddress = null;

    /**
     * The taxable billing address.
     */
    protected ?Addressable $billingAddress = null;

    /**
     * The currency model.
     */
    protected Currency $currency;

    /**
     * The purchasable item.
     */
    protected Purchasable $purchasable;

    /**
     * The cart line.
     */
    protected CartLine $cartLine;

    /**
     * {@inheritDoc}
     */
    public function setShippingAddress(?Addressable $address = null): self
    {
        $this->shippingAddress = $address;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setBillingAddress(?Addressable $address = null): self
    {
        $this->billingAddress = $address;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setPurchasable(Purchasable $purchasable): self
    {
        $this->purchasable = $purchasable;

        return $this;
    }

    /**
     * Set the cart line.
     */
    public function setCartLine(CartLine $cartLine): self
    {
        $this->cartLine = $cartLine;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBreakdown($subTotal): TaxBreakdown
    {
        $breakdown = new TaxBreakdown;

        if ($this->purchasable) {
            $taxClass = $this->purchasable->getTaxClass();
            $taxAmounts = $taxClass->taxRateAmounts;
        } else {
            $taxAmounts = TaxRateAmount::factory(2)->create();
        }

        $currency = Currency::first() ?: Currency::factory()->create();

        $variant = $this->purchasable ?: ProductVariant::factory()->create();

        foreach ($taxAmounts as $amount) {
            $result = round($subTotal * ($amount->percentage / 100));

            $amount = new TaxBreakdownAmount(
                price: new Price((int) $result, $this->currency, $this->purchasable->getUnitQuantity()),
                identifier: "tax_rate_{$amount->taxRate->id}",
                description: $amount->taxRate->name,
                percentage: $amount->percentage
            );
            $breakdown->addAmount($amount);
        }

        return $breakdown;
    }
}
