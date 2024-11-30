# Payflow Table Rate Shipping


# Requirements

- PayflowPHP Admin `>` `1.x`

# Installation

Install via Composer

```
composer require payflowphp/table-rate-shipping
```

Then register the plugin in your service provider

```php
use Payflow\Admin\Support\Facades\PayflowPanel;
use Payflow\Shipping\ShippingPlugin;
// ...

public function register(): void
{
    PayflowPanel::panel(function (Panel $panel) {
        return $panel->plugin(new ShippingPlugin());
    })->register();
    
    // ...
}
```
# Getting Started
This addon provides an easy way for you to add different shipping to your storefront and allow your customers to choose from the different shipping rates you have set up, based on various factors such as zones, minimum spend etc

## Shipping Methods

Shipping Methods are the different ways in which your storefront can send orders to customers, you could also allow customers to collect their order from your store which this addon supports.

## Shipping Zones

Shipping Zones allow you to section of area's of the countries you ship to, providing you with an easy way to offer distinct shipping methods and pricing to each zone, each zone can be restricted by the following:

- Postal codes
- Country
- State/Province (based on country)

## Shipping Rates

Shipping Rates are the prices you offer for each of your shipping zones, they are linked to a shipping method. So for example you might have a Courier Area Shipping Zone and an Everywhere Else Shipping Zone, you can offer different pricing restrictions using the same shipping methods.

## Shipping Exclusion Lists

Sometimes, you might not want to ship certain items to particular Shipping Zone, this is where exclusion lists come in. You can associate purchasables to a list which you can then associate to a shipping zone, if a cart contains any of them then they won't be able to select a shipping rate.

# Storefront usage

This addon uses the shipping modifiers provided by the Payflow core, so you shouldn't need to change your existing implementation.

```php
$options = \Payflow\Base\ShippingManifest::getOptions(
    $cart
);
```

# Advanced usage

## Return available drivers

```php
\Payflow\Shipping\Facades\Shipping::getSupportedDrivers();
```

## Using the driver directly

```php
\Payflow\Shipping\Facades\Shipping::with('ship-by')->resolve(
    new \Payflow\Shipping\DataTransferObjects\ShippingOptionRequest(
        shippingRate: \Payflow\Shipping\Models\ShippingRate $shippingRate,
        cart: \Payflow\Models\Cart $cart
    )
);
```

## Shipping Zones

Each method is optional, the more you add the more strict it becomes.

```php
$shippingZones = Payflow\Shipping\Facades\Shipping::zones()
    ->country(\Payflow\Models\Country $country)
    ->state(\Payflow\Models\State $state)
    ->postcode(
        new \Payflow\Shipping\DataTransferObjects\PostcodeLookup(
            country: \Payflow\Models\Country $country,
            postcode: 'NW1'
        )
    )->get()
    
$shippingZones->map(/* .. */);
```

## Shipping Rates

```php
$shippingRates = \Payflow\Shipping\Facades\Shipping::shippingRates(
    \Payflow\Models\Cart $cart
);
```

## Shipping Options

```php
$shippingOptions = \Payflow\Shipping\Facades\Shipping::shippingOptions(
    \Payflow\Models\Cart $cart
);
```