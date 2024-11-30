<?php

namespace Payflow\Stripe\Pipelines;

use Payflow\Stripe\DataTransferObjects\OrderIntent;

class UpdateOrderFromCharges
{
    public function handle(OrderIntent $orderIntent, \Closure $next) {}
}
