<?php

namespace Payflow\Observers;

use Payflow\Models\ProductVariant;

class ProductVariantObserver
{
    /**
     * Handle the ProductVariant "deleted" event.
     *
     * @return void
     */
    public function deleting(ProductVariant $productVariant)
    {
        $productVariant->prices()->delete();
        $productVariant->values()->detach();
        $productVariant->images()->detach();
    }
}
