<?php

namespace Payflow\Shipping\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Shipping\Interfaces\ShippingMethodManagerInterface;

class Shipping extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return ShippingMethodManagerInterface::class;
    }
}
