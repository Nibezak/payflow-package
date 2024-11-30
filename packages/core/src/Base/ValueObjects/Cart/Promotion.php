<?php

namespace Payflow\Base\ValueObjects\Cart;

use Payflow\DataTypes\Price;

class Promotion
{
    /**
     * Description of the promotion.
     */
    public string $description = '';

    /**
     * Promotion reference.
     */
    public string $reference = '';

    /**
     * Discount amount
     */
    public Price $amount;
}
