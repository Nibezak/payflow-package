# Taxation

## Overview

Taxation is a tricky business and sometimes what Payflow offers simply won't be enough, and we completely understand. This why Taxation is driver based, so you can add your own logic if you need to.

By default we have a `SystemTaxManager` which will use Payflow's internal models and database as outlined above. If you need to write our own implementation, or if you're creating an add on for Tax, you can change the driver in the `config/taxes.php` config file.

```php
<?php

return [
    'driver' => 'system',
];
```

## Writing Your Own Driver

To write your own driver you need to add a class which implements the `Payflow\Base\TaxManager` interface and has the following methods:

```php
<?php

namespace App\Drivers;

use Payflow\Base\TaxDriver;
use Illuminate\Support\Collection;

class TaxJar implements TaxDriver
{
    /**
     * Set the shipping address.
     *
     * @param  \Payflow\DataTypes\Address|null  $address
     * @return self
     */
    public function setShippingAddress(Address $address = null)
    {
        // ...
        return $this;
    }

    /**
     * Set the currency.
     *
     * @param  \Payflow\Models\Currency  $currency
     * @return self
     */
    public function setCurrency(Currency $currency)
    {
        // ...
        return $this;
    }

    /**
     * Set the billing address.
     *
     * @param  \Payflow\DataTypes\Address|null  $address
     * @return self
     */
    public function setBillingAddress(Address $address = null)
    {
        // ...
        return $this;
    }

    /**
     * Set the purchasable item.
     *
     * @param  \Payflow\Base\Purchasable|null  $address
     * @return self
     */
    public function setPurchasable(Purchasable $purchasable)
    {
        // ...
        return $this;
    }

    /**
     * Return the tax breakdown from a given sub total.
     *
     * @param  int  $subTotal
     */
    public function getBreakdown($subTotal): Collection
    {
        return collect([ /* ... */ ]);
    }
}
```

Once you have that, just extend the tax manager in your service provider.

```php

public function boot()
{
    \Payflow\Facades\Taxes::extend('taxjar', function ($app) {
        return $app->make(TaxJar::class);
    })
}
```

You can then set this as the driver in the taxes config.
