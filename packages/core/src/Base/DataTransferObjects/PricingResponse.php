<?php

namespace Payflow\Base\DataTransferObjects;

use Illuminate\Support\Collection;
use Payflow\Models\Price;

class PricingResponse
{
    public function __construct(
        public Price $matched,
        public Price $base,
        public Collection $priceBreaks,
        public Collection $customerGroupPrices,
    ) {
        //
    }
}
