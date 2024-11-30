<?php

namespace Payflow\Tests\Core\Stubs;

use Payflow\Base\OrderReferenceGeneratorInterface;
use Payflow\Models\Order;

class TestOrderReferenceGenerator implements OrderReferenceGeneratorInterface
{
    /**
     * Called just after cart totals are calculated.
     *
     * @return void
     */
    public function generate(Order $order): string
    {
        return 'reference-'.$order->id;
    }
}
