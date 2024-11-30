<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models for indexing
    |--------------------------------------------------------------------------
    |
    | The model listed here will be used to create/populate the indexes.
    | You can provide your own model here to run them all on the same
    | search engine.
    |
    */
    'models' => [
        /*
         * These models are required by the system, do not change them.
         */
        Payflow\Models\Brand::class,
        Payflow\Models\Collection::class,
        Payflow\Models\Customer::class,
        Payflow\Models\Order::class,
        Payflow\Models\Product::class,
        Payflow\Models\ProductOption::class,

        /*
         * Below you can add your own models for indexing...
         */
        // App\Models\Example::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Search engine mapping
    |--------------------------------------------------------------------------
    |
    | You can define what search driver each searchable model should use.
    | If the model isn't defined here, it will use the SCOUT_DRIVER env variable.
    |
    */
    'engine_map' => [
        // Payflow\Models\Product::class => 'algolia',
        // Payflow\Models\Order::class => 'meilisearch',
        // Payflow\Models\Collection::class => 'meilisearch',
    ],

    'indexers' => [
        Payflow\Models\Brand::class => Payflow\Search\BrandIndexer::class,
        Payflow\Models\Collection::class => Payflow\Search\CollectionIndexer::class,
        Payflow\Models\Customer::class => Payflow\Search\CustomerIndexer::class,
        Payflow\Models\Order::class => Payflow\Search\OrderIndexer::class,
        Payflow\Models\Product::class => Payflow\Search\ProductIndexer::class,
        Payflow\Models\ProductOption::class => Payflow\Search\ProductOptionIndexer::class,
    ],

];
