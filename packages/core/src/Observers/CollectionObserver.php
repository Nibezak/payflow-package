<?php

namespace Payflow\Observers;

use Payflow\Jobs\Collections\UpdateProductPositions;
use Payflow\Models\Collection;

class CollectionObserver
{
    /**
     * Handle the Collection "updated" event.
     *
     * @return void
     */
    public function updated(Collection $collection)
    {
        UpdateProductPositions::dispatch($collection);
    }

    /**
     * Handle the Collection "deleting" event.
     *
     * @return void
     */
    public function deleting(Collection $collection)
    {
        $collection->products()->detach();
        $collection->channels()->detach();
        $collection->urls()->delete();
        $collection->customerGroups()->detach();
        $collection->discounts()->detach();
    }
}
