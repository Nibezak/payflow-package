<?php

namespace Payflow\Observers;

use Payflow\Models\ProductOption;
use Payflow\Models\ProductOptionValue;

class ProductOptionObserver
{
    /**
     * Handle the ProductOption "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOption $productOption)
    {
        $productOption->products()->detach();
        $productOption->values()->each(
            fn (ProductOptionValue $optionValue) => $optionValue->delete()
        );
    }
}
