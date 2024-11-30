# Order Extending

## Overview

If you want to add additional functionality to the Order creation process, you can do so using pipelines.

## Pipelines

### Adding an Order Pipeline

All pipelines are defined in `config/payflow/orders.php`

```php
'pipelines' => [
    'creation' => [
        Payflow\Pipelines\Order\Creation\FillOrderFromCart::class,
        Payflow\Pipelines\Order\Creation\CreateOrderLines::class,
        Payflow\Pipelines\Order\Creation\CreateOrderAddresses::class,
        Payflow\Pipelines\Order\Creation\CreateShippingLine::class,
        Payflow\Pipelines\Order\Creation\CleanUpOrderLines::class,
        Payflow\Pipelines\Order\Creation\MapDiscountBreakdown::class,
        // ...
    ],
],
```

You can add your own pipelines to the configuration, they might look something like:

```php
<?php

namespace App\Pipelines\Orders;

use Closure;
use Payflow\DataTypes\Price;
use Payflow\Models\Order;

class CustomOrderPipeline
{
    /**
     * @return void
     */
    public function handle(Order $order, Closure $next)
    {
        // Do something to the cart...

        return $next($order);
    }
}
```

```php
'pipelines' => [
    'creation' => [
        // ...
        App\Pipelines\Orders\CustomOrderPipeline::class,
    ],   
],
```

::: tip
Pipelines will run from top to bottom
:::