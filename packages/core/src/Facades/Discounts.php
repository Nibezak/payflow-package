<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\DiscountManagerInterface;

class Discounts extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return DiscountManagerInterface::class;
    }
}
