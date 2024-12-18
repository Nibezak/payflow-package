<?php

return [

    'connection' => null,

    'table_prefix' => 'payflow_',

    /*
    |--------------------------------------------------------------------------
    | Morph Prefix
    |--------------------------------------------------------------------------
    |
    | If you wish to prefix Payflow's morph mapping in the database, you can
    | set that here e.g. `payflow_product` instead of `product`
    |
    */
    'morph_prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Users Table ID
    |--------------------------------------------------------------------------
    |
    | Payflow adds a relationship to your 'users' table and by default assumes
    | a 'bigint'. You can change this to either an 'int' or 'uuid'.
    |
    */
    'users_id_type' => 'bigint',

    /*
    |--------------------------------------------------------------------------
    | Disable migrations
    |--------------------------------------------------------------------------
    |
    | Prevent Payflow`s default package migrations from running for the core.
    | Set to 'true' to disable.
    |
    */
    'disable_migrations' => false,

];
