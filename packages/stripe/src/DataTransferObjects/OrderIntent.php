<?php

namespace Payflow\Stripe\DataTransferObjects;

use Payflow\Models\Order;
use Stripe\PaymentIntent;

class OrderIntent
{
    public function __construct(
        public Order $order,
        public PaymentIntent $paymentIntent
    ) {}
}
