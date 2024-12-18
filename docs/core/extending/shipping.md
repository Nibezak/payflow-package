# Shipping

## Overview

On your checkout, if your customer has added an item that needs shipping, you're likely going to want to display some shipping options. Currently the best way to do this is to implement your own by adding a `ShippingModifier` and adding using that to determine what shipping options you want to make available and add them to the `ShippingManifest` class.

## Adding a Shipping Modifier

Create your own custom shipping provider:

```php
namespace App\Modifiers;

use Payflow\Base\ShippingModifier;
use Payflow\DataTypes\Price;
use Payflow\DataTypes\ShippingOption;
use Payflow\Facades\ShippingManifest;
use Payflow\Models\Cart;
use Payflow\Models\Currency;
use Payflow\Models\TaxClass;

class CustomShippingModifier extends ShippingModifier
{
    public function handle(Cart $cart, \Closure $next)
    {
        // Get the tax class
        $taxClass = TaxClass::first();

        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Basic Delivery',
                description: 'A basic delivery option',
                identifier: 'BASDEL',
                price: new Price(500, $cart->currency, 1),
                taxClass: $taxClass
            )
        );

        ShippingManifest::addOption(
            new ShippingOption(
                name: 'Pick up in store',
                description: 'Pick your order up in store',
                identifier: 'PICKUP',
                price: new Price(0, $cart->currency, 1),
                taxClass: $taxClass,
                // This is for your reference, so you can check if a collection option has been selected.
                collect: true
            )
        );

        // Or add multiple options, it's your responsibility to ensure the identifiers are unique
        ShippingManifest::addOptions(collect([
            new ShippingOption(
                name: 'Basic Delivery',
                description: 'A basic delivery option',
                identifier: 'BASDEL',
                price: new Price(500, $cart->currency, 1),
                taxClass: $taxClass
            ),
            new ShippingOption(
                name: 'Express Delivery',
                description: 'Express delivery option',
                identifier: 'EXDEL',
                price: new Price(1000, $cart->currency, 1),
                taxClass: $taxClass
            )
        ]));
        
        return $next($cart);
    }
}

```

In your service provider:

```php
public function boot(\Payflow\Base\ShippingModifiers $shippingModifiers)
{
    $shippingModifiers->add(
        CustomShippingModifier::class
    );
}
```
