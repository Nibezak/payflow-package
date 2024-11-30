<?php

namespace Payflow\Admin;

use Filament\Support\Events\FilamentUpgraded;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Payflow\Admin\Auth\Manifest;
use Payflow\Admin\Console\Commands\MakePayflowAdminCommand;
use Payflow\Admin\Database\State\EnsureBaseRolesAndPermissions;
use Payflow\Admin\Events\ChildCollectionCreated;
use Payflow\Admin\Events\CollectionProductDetached;
use Payflow\Admin\Events\CustomerAddressEdited;
use Payflow\Admin\Events\CustomerUserEdited;
use Payflow\Admin\Events\ModelChannelsUpdated;
use Payflow\Admin\Events\ModelPricesUpdated;
use Payflow\Admin\Events\ModelUrlsUpdated;
use Payflow\Admin\Events\ProductAssociationsUpdated;
use Payflow\Admin\Events\ProductCollectionsUpdated;
use Payflow\Admin\Events\ProductCustomerGroupsUpdated;
use Payflow\Admin\Events\ProductPricingUpdated;
use Payflow\Admin\Events\ProductVariantOptionsUpdated;
use Payflow\Admin\Listeners\FilamentUpgradedListener;
use Payflow\Admin\Models\Staff;
use Payflow\Admin\Support\ActivityLog\Manifest as ActivityLogManifest;
use Payflow\Admin\Support\Forms\AttributeData;
use Payflow\Admin\Support\Synthesizers\PriceSynth;

class PayflowPanelProvider extends ServiceProvider
{
    protected $configFiles = [
        'panel',
    ];

    protected $root = __DIR__.'/..';

    public function register(): void
    {
        $this->app->scoped('payflow-panel', function (): PayflowPanelManager {
            return new PayflowPanelManager;
        });

        $this->app->scoped('payflow-access-control', function (): Manifest {
            return new Manifest;
        });

        $this->app->scoped('payflow-activity-log', function (): ActivityLogManifest {
            return new ActivityLogManifest;
        });

        $this->app->scoped('payflow-attribute-data', function (): AttributeData {
            return new AttributeData;
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'payflowpanel');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'payflowpanel');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/payflowpanel'),
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/payflowpanel'),
        ]);

        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom("{$this->root}/config/$config.php", "payflow.$config");
        });

        if ($this->app->runningInConsole()) {
            collect($this->configFiles)->each(function ($config) {
                $this->publishes([
                    "{$this->root}/config/$config.php" => config_path("payflow/$config.php"),
                ], 'payflow');
            });

            $this->commands([
                MakePayflowAdminCommand::class,
            ]);
        }

        Relation::morphMap([
            'staff' => Staff::class,
        ]);

        Event::listen([
            ChildCollectionCreated::class,
            CollectionProductDetached::class,
            CustomerAddressEdited::class,
            CustomerUserEdited::class,
            ProductAssociationsUpdated::class,
            ProductCollectionsUpdated::class,
            ProductPricingUpdated::class,
            ProductCustomerGroupsUpdated::class,
            ProductVariantOptionsUpdated::class,
            ModelChannelsUpdated::class,
            ModelPricesUpdated::class,
            ModelUrlsUpdated::class,
        ], fn ($event) => sync_with_search($event->model));

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/payflowpanel'),
        ], 'public');

        $this->registerAuthGuard();
        $this->registerPermissionManifest();
        $this->registerStateListeners();
        $this->registerPayflowSynthesizer();
        // $this->registerUpgradedListener();
    }

    /**
     * Register our auth guard.
     */
    protected function registerAuthGuard(): void
    {
        $this->app['config']->set('auth.providers.staff', [
            'driver' => 'eloquent',
            'model' => Staff::class,
        ]);

        $this->app['config']->set('auth.guards.staff', [
            'driver' => 'session',
            'provider' => 'staff',
        ]);
    }

    /**
     * Register our permissions manifest.
     */
    protected function registerPermissionManifest(): void
    {
        Gate::after(function ($user, $ability) {
            // Are we trying to authorize something within the admin panel?
            $permission = $this->app->get('payflow-access-control')->getPermissions()->first(fn ($permission) => $permission->handle === $ability);
            if ($permission) {
                return $user->admin || $user->hasPermissionTo($ability);
            }
        });
    }

    protected function registerUpgradedListener(): void
    {
        Event::listen(FilamentUpgraded::class, FilamentUpgradedListener::class);
    }

    protected function registerStateListeners()
    {
        $states = [
            EnsureBaseRolesAndPermissions::class,
        ];

        foreach ($states as $state) {
            $class = new $state;

            Event::listen(
                [MigrationsStarted::class],
                [$class, 'prepare']
            );

            Event::listen(
                [MigrationsEnded::class, NoPendingMigrations::class],
                [$class, 'run']
            );
        }
    }

    protected function registerPayflowSynthesizer(): void
    {
        \Payflow\Admin\Support\Facades\AttributeData::synthesizeLivewireProperties();
        Livewire::propertySynthesizer(PriceSynth::class);
    }
}
