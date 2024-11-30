# Models

## Overview

Payflow provides a number of Eloquent Models and quite often in custom applications you will want to add your own relationships and functionality to these models.

::: warning
We highly suggest using your own Eloquent Models to add additional data, rather than trying to change fields on the core Payflow models.
:::

## Replaceable Models
All Payflow models are replaceable, this means you can instruct Payflow to use your own custom model, throughout the ecosystem, using dependency injection.


### Registration
We recommend registering your own models for your application within the boot method of your Service Provider.

When registering your models, you will need to set the Payflow model's contract as the first argument then your own model implementation for the second.


```php
/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \App\Model\Product::class,
    );
}
```

#### Registering multiple Payflow models.

If you have multiple models you want to replace, instead of manually replacing them one by one, you can specify a directory for Payflow to look in for Payflow models to use.
This assumes that each model extends its counterpart model i.e. `App\Models\Product` extends `Payflow\Models\Product`.

```php
/**
 * Bootstrap any application services.
 *
 * @return void
 */
public function boot()
{
    \Payflow\Facades\ModelManifest::addDirectory(
        __DIR__.'/../Models'
    );
}
```

### Route binding

Route binding is supported for your own routes and simply requires the relevant contract class to be injected.

```php
Route::get('products/{id}', function (\Payflow\Models\Contracts\Product $product) {
    $product; // App\Models\Product
});
```

### Relationship support

If you replace a model which is used in a relationship, you can easily get your own model back via relationship methods. Assuming we want to use our own instance of `App\Models\ProductVariant`.

```php
// In our service provider.
public function boot()
{
    \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\ProductVariant::class,
        \App\Model\ProductVariant::class,
    );
}

// Somewhere else in your code...

$product = \Payflow\Models\Product::first();
$product->variants->first(); // App\Models\ProductVariant
```

### Static call forwarding

If you have custom methods in your own model, you can call those functions directly from the Payflow model instance.

Assuming we want to provide a new function to a product variant model.

```php
<?php

namespace App\Models;

class ProductVariant extends \Payflow\Models\ProductVariant
{
    public function someCustomMethod()
    {
        return 'Hello!';
    }
}
```

```php
// In your service provider.
public function boot()
{
    \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\ProductVariant::class,
        \App\Model\ProductVariant::class,
    );
}
```

Somewhere else in your app...

```php
\Payflow\Models\ProductVariant::someCustomMethod(); // Hello!
\App\Models\ProductVariant::someCustomMethod(); // Hello!
```

### Observers

If you have observers in your app which call `observe` on the Payflow model, these will still work as intended when you replace any of the models, this means if you 
want to add your own custom observers, you can just reference the Payflow model and everything will be forwarded to the appropriate class.

```php
\Payflow\Models\Product::observe(/** .. */);
```

## Dynamic Eloquent Relationships

If you don't need to completely override or extend the Payflow models using the techniques above, you are still free to resolve relationships dynamically as Laravel provides out the box.

e.g. 

```php
use Payflow\Models\Order;
use App\Models\Ticket;
 
Order::resolveRelationUsing('ticket', function ($orderModel) {
    return $orderModel->belongsTo(Ticket::class, 'ticket_id');
});
```

See [https://laravel.com/docs/eloquent-relationships#dynamic-relationships](https://laravel.com/docs/eloquent-relationships#dynamic-relationships) for more information.
