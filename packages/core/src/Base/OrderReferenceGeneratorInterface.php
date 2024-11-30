<?php

namespace Payflow\Base;

use Payflow\Models\Order;

interface OrderReferenceGeneratorInterface
{
    /**
     * Generate a reference for the order.
     */
    public function generate(Order $order): string;
}
