<?php

namespace Payflow\Base;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Models\Customer;

interface PayflowUser
{
    public function customers(): BelongsToMany;

    public function carts(): HasMany;

    public function latestCustomer(): ?Customer;

    public function orders(): HasMany;
}
