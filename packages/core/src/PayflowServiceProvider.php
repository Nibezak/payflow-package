<?php

namespace Payflow;

use Cartalyst\Converter\Laravel\Facades\Converter;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Payflow\Addons\Manifest;
use Payflow\Base\AttributeManifest;
use Payflow\Base\AttributeManifestInterface;
use Payflow\Base\CartLineModifiers;
use Payflow\Base\CartModifiers;
use Payflow\Base\CartSessionInterface;
use Payflow\Base\DiscountManagerInterface;
use Payflow\Base\FieldTypeManifest;
use Payflow\Base\FieldTypeManifestInterface;
use Payflow\Base\ModelManifest;
use Payflow\Base\ModelManifestInterface;
use Payflow\Base\OrderModifiers;
use Payflow\Base\OrderReferenceGenerator;
use Payflow\Base\OrderReferenceGeneratorInterface;
use Payflow\Base\PaymentManagerInterface;
use Payflow\Base\PricingManagerInterface;
use Payflow\Base\ShippingManifest;
use Payflow\Base\ShippingManifestInterface;
use Payflow\Base\ShippingModifiers;
use Payflow\Base\StorefrontSessionInterface;
use Payflow\Base\TaxManagerInterface;
use Payflow\Console\Commands\AddonsDiscover;
use Payflow\Console\Commands\Import\AddressData;
use Payflow\Console\Commands\MigrateGetCandy;
use Payflow\Console\Commands\Orders\SyncNewCustomerOrders;
use Payflow\Console\Commands\PruneCarts;
use Payflow\Console\Commands\ScoutIndexerCommand;
use Payflow\Console\InstallPayflow;
use Payflow\Database\State\ConvertBackOrderPurchasability;
use Payflow\Database\State\ConvertProductTypeAttributesToProducts;
use Payflow\Database\State\ConvertTaxbreakdown;
use Payflow\Database\State\EnsureBrandsAreUpgraded;
use Payflow\Database\State\EnsureDefaultTaxClassExists;
use Payflow\Database\State\EnsureMediaCollectionsAreRenamed;
use Payflow\Database\State\MigrateCartOrderRelationship;
use Payflow\Database\State\PopulateProductOptionLabelWithName;
use Payflow\Listeners\CartSessionAuthListener;
use Payflow\Managers\CartSessionManager;
use Payflow\Managers\DiscountManager;
use Payflow\Managers\PaymentManager;
use Payflow\Managers\PricingManager;
use Payflow\Managers\StorefrontSessionManager;
use Payflow\Managers\TaxManager;
use Payflow\Models\Address;
use Payflow\Models\CartLine;
use Payflow\Models\Channel;
use Payflow\Models\Collection;
use Payflow\Models\Currency;
use Payflow\Models\CustomerGroup;
use Payflow\Models\Language;
use Payflow\Models\Order;
use Payflow\Models\OrderLine;
use Payflow\Models\ProductOption;
use Payflow\Models\ProductOptionValue;
use Payflow\Models\ProductVariant;
use Payflow\Models\Transaction;
use Payflow\Models\Url;
use Payflow\Observers\AddressObserver;
use Payflow\Observers\CartLineObserver;
use Payflow\Observers\ChannelObserver;
use Payflow\Observers\CollectionObserver;
use Payflow\Observers\CurrencyObserver;
use Payflow\Observers\CustomerGroupObserver;
use Payflow\Observers\LanguageObserver;
use Payflow\Observers\MediaObserver;
use Payflow\Observers\OrderLineObserver;
use Payflow\Observers\OrderObserver;
use Payflow\Observers\ProductOptionObserver;
use Payflow\Observers\ProductOptionValueObserver;
use Payflow\Observers\ProductVariantObserver;
use Payflow\Observers\TransactionObserver;
use Payflow\Observers\UrlObserver;

class PayflowServiceProvider extends ServiceProvider
{
    protected $configFiles = [
        'cart',
        'cart_session',
        'database',
        'discounts',
        'media',
        'orders',
        'payments',
        'pricing',
        'search',
        'shipping',
        'taxes',
        'urls',
    ];

    protected $root = __DIR__.'/..';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        collect($this->configFiles)->each(function ($config) {
            $this->mergeConfigFrom("{$this->root}/config/$config.php", "payflow.$config");
        });

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'payflow');

        $this->registerAddonManifest();

        $this->app->singleton(CartModifiers::class, function () {
            return new CartModifiers;
        });

        $this->app->singleton(CartLineModifiers::class, function () {
            return new CartLineModifiers;
        });

        $this->app->singleton(OrderModifiers::class, function () {
            return new OrderModifiers;
        });

        $this->app->singleton(CartSessionInterface::class, function ($app) {
            return $app->make(CartSessionManager::class);
        });

        $this->app->singleton(StorefrontSessionInterface::class, function ($app) {
            return $app->make(StorefrontSessionManager::class);
        });

        $this->app->singleton(ShippingModifiers::class, function ($app) {
            return new ShippingModifiers;
        });

        $this->app->singleton(ShippingManifestInterface::class, function ($app) {
            return $app->make(ShippingManifest::class);
        });

        $this->app->singleton(OrderReferenceGeneratorInterface::class, function ($app) {
            return $app->make(OrderReferenceGenerator::class);
        });

        $this->app->singleton(AttributeManifestInterface::class, function ($app) {
            return $app->make(AttributeManifest::class);
        });

        $this->app->singleton(FieldTypeManifestInterface::class, function ($app) {
            return $app->make(FieldTypeManifest::class);
        });

        $this->app->singleton(ModelManifestInterface::class, function ($app) {
            return $app->make(ModelManifest::class);
        });

        $this->app->bind(PricingManagerInterface::class, function ($app) {
            return $app->make(PricingManager::class);
        });

        $this->app->singleton(TaxManagerInterface::class, function ($app) {
            return $app->make(TaxManager::class);
        });

        $this->app->singleton(PaymentManagerInterface::class, function ($app) {
            return $app->make(PaymentManager::class);
        });

        $this->app->singleton(DiscountManagerInterface::class, function ($app) {
            return $app->make(DiscountManager::class);
        });

        \Payflow\Facades\ModelManifest::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! config('payflow.database.disable_migrations', false)) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->registerObservers();
        $this->registerBuilderMacros();
        $this->registerBlueprintMacros();
        $this->registerStateListeners();

        \Payflow\Facades\ModelManifest::morphMap();

        if ($this->app->runningInConsole()) {
            collect($this->configFiles)->each(function ($config) {
                $this->publishes([
                    "{$this->root}/config/$config.php" => config_path("payflow/$config.php"),
                ], 'payflow');
            });

            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/payflow'),
            ], 'payflow.translation');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'payflow.migrations');

            $this->commands([
                InstallPayflow::class,
                AddonsDiscover::class,
                AddressData::class,
                ScoutIndexerCommand::class,
                MigrateGetCandy::class,
                SyncNewCustomerOrders::class,
                PruneCarts::class,
            ]);

            if (config('payflow.cart.prune_tables.enabled', false)) {
                $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                    $schedule->command('payflow:prune:carts')->daily();
                });
            }
        }

        Arr::macro('permutate', [\Payflow\Utils\Arr::class, 'permutate']);

        // Handle generator
        Str::macro('handle', function ($string) {
            return Str::slug($string, '_');
        });

        Converter::setMeasurements(
            config('payflow.shipping.measurements', [])
        );

        Event::listen(
            Login::class,
            [CartSessionAuthListener::class, 'login']
        );

        Event::listen(
            Logout::class,
            [CartSessionAuthListener::class, 'logout']
        );
    }

    protected function registerAddonManifest()
    {
        $this->app->instance(Manifest::class, new Manifest(
            new Filesystem,
            $this->app->basePath(),
            $this->app->bootstrapPath().'/cache/payflow_addons.php'
        ));
    }

    protected function registerStateListeners()
    {
        $states = [
            ConvertProductTypeAttributesToProducts::class,
            EnsureDefaultTaxClassExists::class,
            EnsureBrandsAreUpgraded::class,
            EnsureMediaCollectionsAreRenamed::class,
            PopulateProductOptionLabelWithName::class,
            MigrateCartOrderRelationship::class,
            ConvertTaxbreakdown::class,
            ConvertBackOrderPurchasability::class,
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

    /**
     * Register the observers used in Payflow.
     */
    protected function registerObservers(): void
    {
        Channel::observe(ChannelObserver::class);
        CustomerGroup::observe(CustomerGroupObserver::class);
        Language::observe(LanguageObserver::class);
        Currency::observe(CurrencyObserver::class);
        Url::observe(UrlObserver::class);
        Collection::observe(CollectionObserver::class);
        CartLine::observe(CartLineObserver::class);
        ProductOption::observe(ProductOptionObserver::class);
        ProductOptionValue::observe(ProductOptionValueObserver::class);
        ProductVariant::observe(ProductVariantObserver::class);
        Order::observe(OrderObserver::class);
        OrderLine::observe(OrderLineObserver::class);
        Address::observe(AddressObserver::class);
        Transaction::observe(TransactionObserver::class);

        if ($mediaModel = config('media-library.media_model')) {
            $mediaModel::observe(MediaObserver::class);
        }
    }

    protected function registerBuilderMacros(): void
    {
        Builder::macro('orderBySequence', function (array $ids) {
            /** @var Builder $this */
            $driver = $this->getConnection()->getDriverName();

            if (empty($ids)) {
                return $this;
            }

            if ($driver === 'mysql') {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                return $this->orderByRaw("FIELD(id, {$placeholders})", $ids);
            }

            if ($driver === 'pgsql') {
                $orderCases = '';
                foreach ($ids as $index => $id) {
                    $orderCases .= "WHEN id = $id THEN $index ";
                }

                return $this->orderByRaw("CASE $orderCases ELSE ".count($ids).' END');
            }

            return $this;
        });
    }

    /**
     * Register the blueprint macros.
     */
    protected function registerBlueprintMacros(): void
    {
        Blueprint::macro('scheduling', function () {
            /** @var Blueprint $this */
            $this->boolean('enabled')->default(false)->index();
            $this->timestamp('starts_at')->nullable()->index();
            $this->timestamp('ends_at')->nullable()->index();
        });

        Blueprint::macro('dimensions', function () {
            /** @var Blueprint $this */
            $columns = ['length', 'width', 'height', 'weight', 'volume'];
            foreach ($columns as $column) {
                $this->decimal("{$column}_value", 10, 4)->default(0)->nullable()->index();
                $this->string("{$column}_unit")->default('mm')->nullable();
            }
        });

        Blueprint::macro('userForeignKey', function ($field_name = 'user_id', $nullable = false) {
            /** @var Blueprint $this */
            $userModel = config('auth.providers.users.model');

            $type = config('payflow.database.users_id_type', 'bigint');

            if ($type == 'uuid') {
                $this->foreignUuId($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel)->getTable()
                    );
            } elseif ($type == 'int') {
                $this->unsignedInteger($field_name)->nullable($nullable);
                $this->foreign($field_name)->references('id')->on('users');
            } else {
                $this->foreignId($field_name)
                    ->nullable($nullable)
                    ->constrained(
                        (new $userModel)->getTable()
                    );
            }
        });
    }
}
