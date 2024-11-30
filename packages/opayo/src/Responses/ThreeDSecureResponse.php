<?php

namespace Payflow\Opayo\Responses;

use Payflow\Base\DataTransferObjects\PaymentAuthorize;

class ThreeDSecureResponse extends PaymentAuthorize
{
    public function __construct(
        public bool $success = false,
        public ?string $status = null,
        public ?string $acsUrl = null,
        public ?string $acsTransId = null,
        public ?string $dsTransId = null,
        public ?string $cReq = null,
        public ?string $paReq = null,
        public ?string $transactionId = null,
        public ?string $message = null
    ) {
        //
    }
}
