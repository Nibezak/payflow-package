<?php

namespace Payflow\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Brand
{
    /**
     * Return the product relationship.
     */
    public function products(): HasMany;
}
