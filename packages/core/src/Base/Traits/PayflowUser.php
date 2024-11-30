<?php

namespace Payflow\Base\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Models\Cart;
use Payflow\Models\Customer;
use Payflow\Models\Order;

trait PayflowUser
{
    public function customers(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(Customer::class, "{$prefix}customer_user");
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function latestCustomer(): ?Customer
    {
        return $this->customers()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();
    }

    /**
     * Return the user orders relationship.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
