<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\StorefrontSessionInterface;

class StorefrontSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return StorefrontSessionInterface::class;
    }
}
