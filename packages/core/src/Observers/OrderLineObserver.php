<?php

namespace Payflow\Observers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Payflow\Base\Purchasable;
use Payflow\Exceptions\NonPurchasableItemException;
use Payflow\Models\OrderLine;

class OrderLineObserver
{
    /**
     * Handle the OrderLine "creating" event.
     *
     * @return void
     */
    public function creating(OrderLine $orderLine)
    {
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }

    /**
     * Handle the OrderLine "updated" event.
     *
     * @return void
     */
    public function updating(OrderLine $orderLine)
    {
        $purchasableModel = class_exists($orderLine->purchasable_type) ?
            $orderLine->purchasable_type :
            Relation::getMorphedModel($orderLine->purchasable_type);

        if (! $purchasableModel || ! in_array(Purchasable::class, class_implements($purchasableModel, true))) {
            throw new NonPurchasableItemException($purchasableModel);
        }
    }
}
