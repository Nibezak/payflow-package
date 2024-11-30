<?php

namespace Payflow\Observers;

use Payflow\Models\ProductOptionValue;

class ProductOptionValueObserver
{
    /**
     * Handle the ProductOptionValue "deleting" event.
     *
     * @return void
     */
    public function deleting(ProductOptionValue $productOptionValue)
    {
        $productOptionValue->variants()->detach();
    }
}
