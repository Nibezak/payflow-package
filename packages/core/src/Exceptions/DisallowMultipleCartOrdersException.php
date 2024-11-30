<?php

namespace Payflow\Exceptions;

class DisallowMultipleCartOrdersException extends PayflowException
{
    public function __construct()
    {
        $this->message = __('payflow::exceptions.disallow_multiple_cart_orders');
    }
}
