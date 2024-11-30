<?php

namespace Payflow\Base\DataTransferObjects;

class PaymentRefund
{
    public function __construct(
        public bool $success = false,
        public ?string $message = null
    ) {
        //
    }
}
