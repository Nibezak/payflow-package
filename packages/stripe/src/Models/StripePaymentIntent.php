<?php

namespace Payflow\Stripe\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Payflow\Base\BaseModel;
use Payflow\Models\Cart;

class StripePaymentIntent extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
