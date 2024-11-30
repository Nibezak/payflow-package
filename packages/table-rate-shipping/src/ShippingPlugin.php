<?php

namespace Payflow\Shipping;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\Support\Facades\FilamentIcon;
use Payflow\Shipping\Filament\Resources\ShippingExclusionListResource;
use Payflow\Shipping\Filament\Resources\ShippingMethodResource;
use Payflow\Shipping\Filament\Resources\ShippingZoneResource;

class ShippingPlugin implements Plugin
{
    public function getId(): string
    {
        return 'shipping';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    public function register(Panel $panel): void
    {
        if (! config('payflow.shipping-tables.enabled')) {
            return;
        }

        $panel->navigationGroups([
            NavigationGroup::make('shipping')
                ->label(
                    fn () => __('payflowpanel.shipping::plugin.navigation.group')
                ),
        ])->resources([
            ShippingMethodResource::class,
            ShippingZoneResource::class,
            ShippingExclusionListResource::class,
        ]);

        FilamentIcon::register([
            'payflow::shipping-rates' => 'lucide-coins',
            'payflow::shipping-zones' => 'lucide-globe-2',
            'payflow::shipping-methods' => 'lucide-truck',
            'payflow::shipping-exclusion-lists' => 'lucide-package-minus',
        ]);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel;
    }

    // ...
}
