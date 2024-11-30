<?php

namespace Payflow\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface AttributeGroup
{
    /**
     * Return the group attributes relationship.
     */
    public function attributes(): HasMany;
}
