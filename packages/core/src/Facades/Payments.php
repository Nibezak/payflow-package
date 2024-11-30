<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\Facade;
use Payflow\Base\PaymentManagerInterface;

class Payments extends Facade
{
    public static function getFacadeAccessor()
    {
        return PaymentManagerInterface::class;
    }
}
