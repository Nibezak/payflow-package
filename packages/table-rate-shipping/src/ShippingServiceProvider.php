<?php

namespace Payflow\Shipping;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Payflow\Base\ShippingModifiers;
use Payflow\Facades\ModelManifest;
use Payflow\Models\CustomerGroup;
use Payflow\Models\Order;
use Payflow\Models\Product;
use Payflow\Shipping\Interfaces\ShippingMethodManagerInterface;
use Payflow\Shipping\Managers\ShippingManager;
use Payflow\Shipping\Models\ShippingExclusion;
use Payflow\Shipping\Models\ShippingExclusionList;
use Payflow\Shipping\Models\ShippingMethod;
use Payflow\Shipping\Models\ShippingRate;
use Payflow\Shipping\Models\ShippingZone;
use Payflow\Shipping\Models\ShippingZonePostcode;
use Payflow\Shipping\Observers\OrderObserver;

class ShippingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipping-tables.php', 'payflow.shipping-tables');
    }

    public function boot(ShippingModifiers $shippingModifiers)
    {
        if (! config('payflow.shipping-tables.enabled')) {
            return;
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'payflowpanel.shipping');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'shipping');

        $shippingModifiers->add(
            ShippingModifier::class,
        );

        Order::observe(OrderObserver::class);

        Order::resolveRelationUsing('shippingZone', function ($orderModel) {
            $prefix = config('payflow.database.table_prefix');

            return $orderModel->belongsToMany(
                ShippingZone::class,
                "{$prefix}order_shipping_zone"
            )->withTimestamps();
        });

        CustomerGroup::resolveRelationUsing('shippingMethods', function ($customerGroup) {
            $prefix = config('payflow.database.table_prefix');

            return $customerGroup->belongsToMany(
                ShippingMethod::class,
                "{$prefix}customer_group_shipping_method"
            )->withTimestamps();
        });

        Product::resolveRelationUsing('shippingExclusions', function ($product) {
            return $product->morphMany(ShippingExclusion::class, 'purchasable');
        });

        $this->app->bind(ShippingMethodManagerInterface::class, function ($app) {
            return $app->make(ShippingManager::class);
        });

        ModelManifest::addDirectory(
            __DIR__.'/Models'
        );

        Relation::morphMap([
            'shipping_exclusion' => ShippingExclusion::modelClass(),
            'shipping_exclusion_list' => ShippingExclusionList::modelClass(),
            'shipping_method' => ShippingMethod::modelClass(),
            'shipping_rate' => ShippingRate::modelClass(),
            'shipping_zone' => ShippingZone::modelClass(),
            'shipping_zone_postcode' => ShippingZonePostcode::modelClass(),
        ]);
    }
}
