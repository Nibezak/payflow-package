<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\CartSessionInterface;

class CartSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return CartSessionInterface::class;
    }
}
