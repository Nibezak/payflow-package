<?php

namespace Payflow\Paypal\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Paypal\PaypalInterface;

class Paypal extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return PaypalInterface::class;
    }
}
